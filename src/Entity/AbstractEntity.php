<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Exception\RuntimeException;
    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the tables in the database
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractEntity implements EntityInterface
    {
        /**
         * @var EntityManager
         */
        protected $manager;



        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName;



        /**
         * The names of the columns
         *
         * @var array
         */
        protected $columns;



        /**
         * The columns with options
         *
         * @var array
         */
        protected $columnsWithOptions = [];



        /**
         * The types of columns allowed
         */
        const TYPE_COLUMNS_ALLOWED = [
            "TEXT", "DATE",
            "TININT", "SMALLINT", "INT", "MEDIUMINT", "BIGINT",
            "DECIMAL", "FLOAT", "DOUBLE", "REAL",
            "DATE", "DATETIME", "TIME", "TIMESTAMP", "YEAR",
            "CHAR", "VARCHAR",
            "TEXT", "TINYTEXT", "MEDIUMTEXT", "LONGTEXT",
            "BINARY", "VARBINARY",
            "BLOB", "TINYBLOB", "MEDIUMBLOB", "LONGBLOB"
        ];



        /**
         * {@inheritDoc}
         */
        public function addTable () : self
        {
            $sql = "CREATE TABLE IF NOT EXISTS " . $this->getTableName() . " (" . "\n";
            $sql .= $this->generateColumns();
            $sql .= $this->setPrimaryKey();
            $sql .= $this->setConstraint();
            $sql .= ")" . "\n";
            $sql .= "ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

            $this->manager->prepareAndExecute($this->manager->getConnection(), $sql);

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function dropTable () : self
        {
            $sql = "DROP TABLE " . $this->getTableName();
            $this->manager->prepareAndExecute($this->manager->getConnection(), $sql);

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function emptyTable () : self
        {
            $sql = "TRUNCATE TABLE " . $this->getTableName();
            $this->manager->prepareAndExecute($this->manager->getConnection(), $sql);

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function emptyAllTable (...$tablesName) : self
        {
            $this->manager->prepareAndExecute($this->manager->getConnection(), "SET FOREIGN_KEY_CHECKS = 0");

            foreach ($tablesName as $table) {
                $this->manager->prepareAndExecute($this->manager->getConnection(), "TRUNCATE TABLE " . $table->getTableName());
            }

            $this->manager
                ->prepareAndExecute($this->manager->getConnection(), "TRUNCATE TABLE " . $this->getTableName())
                ->prepareAndExecute($this->manager->getConnection(), "SET FOREIGN_KEY_CHECKS = 1");

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function createColumns(EntityManager $manager)
        {
            $this->manager = $manager;

            $this->columns = $this
                ->setColumns($this)
                ->getColumns();
        }



        /**
         * Get the columns
         *
         * @return array
         */
        public function getColumns() : array
        {
            return $this->columns;
        }



        /**
         * {@inheritDoc}
         */
        public function setColumns($entity) : self
        {
            $this->columns = get_object_vars($entity);

            $items = [];
            foreach ($this->columns as $k => $v) {
                $items[] = $k;
            }

            $this->columns = array_slice($items, 0, -4);

            return $this;
        }



        /**
         * Set the primary key
         *
         * @return string
         */
        private function setPrimaryKey () : string
        {
            foreach ($this->columnsWithOptions as $item) {
                if (count($item) >= 8) {
                    if ($item[7] === true) {
                        $primaryKey[] = $item[array_key_first($item)];
                    }
                }

                $separator = (count($item) >= 8) ? "," : null;
            }

            $primaryKey = implode(", ", $primaryKey);

            return "PRIMARY KEY ($primaryKey)$separator" . "\n";
        }



        /**
         * Set the constraints
         *
         * @return string|null
         */
        private function setConstraint () : ?string
        {
            $constraints = [];

            foreach ($this->columnsWithOptions as $item) {
                if (count($item) === 9) {
                    if (!empty($item[8])) {
                        $columnParts = explode("_", $item[0]);

                        $constraintPart["constraint"] = "CONSTRAINT fk_$columnParts[0]";
                        $constraintPart["foreignKey"] = "FOREIGN KEY ($item[0])";
                        $constraintPart["references"] = "REFERENCES $columnParts[0] ($columnParts[1])";
                        $constraintPart["delete"] = "ON DELETE " . $item[8]["delete"];
                        $constraintPart["update"] = "ON UPDATE " . $item[8]["update"];

                        $constraints[] = implode(" ", $constraintPart);
                    }
                }
            }

            return !empty($constraints) ? implode(", " . "\n", $constraints) : null;
        }



        /**
         * Generate the columns
         *
         * @return string
         */
        private function generateColumns () : string
        {
            $items = [];
            foreach ($this->columnsWithOptions as $v) {
                $items[] = trim($this->columns(
                    array_key_exists("0", $v) ? $v[0] : null,
                    array_key_exists("1", $v) ? $v[1] : null,
                    array_key_exists("2", $v) ? $v[2] : null,
                    array_key_exists("3", $v) ? $v[3] : false, 
                    array_key_exists("4", $v) ? $v[4] : false,
                    array_key_exists("5", $v) ? $v[5] : false,
                    array_key_exists("6", $v) ? $v[6] : null,
                ));
            }

            return implode("," . "\n", $items) . "," . "\n";
        }



        /**
         * Set a column of a table
         *
         * @return string
         */
        private function columns (string $name, string $type, ?int $option = null, bool $isNull = false, bool $unsigned = false, bool $autoIncrement = false, $defaultValue = null) : string
        {
            $type = strtoupper($type);

            $name = "$name ";
            $isNull = $isNull ? "DEFAULT NULL " : "NOT NULL ";
            $autoIncrement = $autoIncrement ? "AUTO_INCREMENT" : null;
            $unsigned = $unsigned ? "UNSIGNED " : null;

            $option = !is_null($option) ? "($option)" : null;
            $defaultValue = !is_null($defaultValue) ? "$defaultValue " : null;

            if (in_array($type, self::TYPE_COLUMNS_ALLOWED)) {
                $type = $type . $option . " ";
                return $name . $type . $unsigned . $isNull . $autoIncrement . $defaultValue;
            }
        }



        /**
         * {@inheritDoc}
         */
        public function setColumnsWithOptions(string $name, string $type, ?int $option = null, bool $isNull = false, bool $unsigned = false, bool $autoIncrement = false, $defaultValue = null, $primaryKey = false, array $constraint = []) : self
        {
            if (in_array($name, $this->columns)) {
                $this->columnsWithOptions[] = func_get_args();
            } else {
                throw new RuntimeException("The value of the 1st parameter is not allowed. The allowed values are : " . implode(", ", $this->columns));
            }
            
            return $this;
        }



        /**
         * Get the name of the current table
         *
         * @return string
         */
        public function getTableName () : string
        {
            if (is_null($this->tableName)) {
                $pos = strrpos(get_class($this), "\\") + 1;
                $this->tableName = substr(get_class($this), $pos);
            }

            return strtolower($this->tableName);
        }
    }