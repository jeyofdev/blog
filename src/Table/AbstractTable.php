<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Exception\ExecuteQueryFailedException;
    use jeyofdev\php\blog\Exception\RuntimeException;
    use PDO;
    use PDOStatement;


    /**
     * Manage the standard queries
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractTable implements TableInterface, QueryInterface
    {
        /**
         * @var PDO
         */
        protected $connection;



        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName;



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className;



        /**
         * The instance of the table (ex PostTable)
         *
         * @var object
         */
        protected $table;



        /**
         * @var PDOStatement
         */
        protected $query;



        /**
         * @param PDO $connection
         */
        public function __construct (PDO $connection)
        {
            $this->connection = $connection;

            if (is_null($this->tableName) || is_null($this->className)) {
                $pos = strrpos(get_class($this), "\\") + 1;
                $name = substr(get_class($this), $pos);

                if (is_null($this->tableName)) {
                    throw (new RuntimeException())->propertyValueIsNull($name, "tableName");
                } else if (is_null($this->className)) {
                    throw (new RuntimeException())->propertyValueIsNull($name, "className");
                }
            } else {
                $this->table = new $this->className();
            }
        }



        /**
         * {@inheritDoc}
         */
        public function find (array $params, $fetchMode = PDO::FETCH_CLASS)
        {
            $where = $this->generateWhere($params);

            $sql = "SELECT * FROM {$this->tableName} WHERE $where";
            $query = $this->prepare($sql, $params, $fetchMode);

            return $this->fetch($query);
        }



        /**
         * {@inheritDoc}
         */
        public function findAllBy (?string $orderBy = null, string $direction = "ASC", ?int $limit = null, ?int $offset = null, int $fetchMode = PDO::FETCH_CLASS) : array
        {
            $sql = "SELECT * FROM {$this->tableName}";

            if (!is_null($orderBy)) {
                $direction = strtoupper($direction);
                $sql .= " ORDER BY $orderBy $direction";
            }

            if (!is_null($limit)) {
                $sql .= " LIMIT $limit";
            }

            if (!is_null($offset)) {
                $sql .= " OFFSET $offset";
            }
            
            $query = $this->query($sql, $fetchMode);

            return $this->fetchAll($query);
        }



        /**
         * {@inheritDoc}
         */
        public function delete (array $params) : void
        {
            $where = $this->generateWhere($params);

            $sql = "DELETE FROM {$this->tableName} WHERE $where";
            $query = $this->prepare($sql, $params);

            if (!$query) {
                throw (new ExecuteQueryFailedException())->deleteHasFailed("id", $this->tableName);
            }
        }



        /**
         * {@inheritDoc}
         */
        public function countAll (string $column, int $fetchMode = PDO::FETCH_NUM) : int
        {
            $sql = "SELECT COUNT($column) FROM {$this->tableName}";
            $query = $this->query($sql, $fetchMode);

            return $this->fetch($query)[0];
        }



        /**
         * Generate the where clause of a query
         *
         * @return string|null
         */
        private function generateWhere (array $params) : ?string
        {
            if (!is_null($params)) {
                $items = [];
                foreach ($params as $k => $v) {
                    $items[] = "$k = :$k";
                }
            }

            return isset($items) ? implode(", ", $items) : null;
        }



        /**
         * {@inheritDoc}
         */
        public function query (string $sql, int $fetchMode = PDO::FETCH_CLASS) : PDOStatement
        {
            $this->query = $this->connection->query($sql);
            $this->setFetchMode($fetchMode);

            return $this->query;
        }



        /**
         * {@inheritDoc}
         */
        public function prepare (string $sql, array $params = [], int $fetchMode = PDO::FETCH_CLASS) : PDOStatement
        {
            $this->query = $this->connection->prepare($sql);
            $this->query->execute($params);
            $this->setFetchMode($fetchMode);

            return $this->query;
        }



        /**
         * {@inheritDoc}
         */
        public function fetch ()
        {
            return $this->query->fetch();
        }



        /**
         * {@inheritDoc}
         */
        public function fetchAll () : array
        {
            return $this->query->fetchAll();
        }



        /**
         * {@inheritDoc}
         */
        public function setFetchMode (int $fetchMode) : void
        {
            if ($fetchMode === PDO::FETCH_CLASS) {
                $this->query->setFetchMode($fetchMode, $this->className);
            } else {
                $this->query->setFetchMode($fetchMode);
            }
        }
    }