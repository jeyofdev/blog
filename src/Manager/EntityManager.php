<?php

    namespace jeyofdev\php\blog\Manager;


    use DateTime;
    use jeyofdev\php\blog\Database\Database;
    use PDO;


    /**
     * Set the sql request
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class EntityManager implements EntityManagerInterface
    {
        /**
         * @var Database
         */
        private $database;



        /**
         * @var PDO
         */
        private $connection;



        /**
         * @param Database $database
         */
        public function __construct (Database $database)
        {
            $this->database = $database;
            $this->connection = $this->database->getConnection($this->database->getDb_name());
        }



        /**
         * {@inheritDoc}
         */
        public function insert($entity) : void
        {
            $columns = [];
            $params = [];

            foreach ($entity->getColumns() as $item) {
                if ($item !== "id") {
                    $columns[] = "$item = :$item";

                    $method = "get" . ucfirst($item);

                    if ($entity->$method() instanceof DateTime) {
                        $params[$item] = $entity->$method()->format("Y-m-d H:i:s");
                    } else {
                        $params[$item] = $entity->$method();
                    }
                }
            }

            $columns = implode(", ", $columns);
            $sql = "INSERT INTO {$entity->getTableName()} SET {$columns}";

            $this->prepareAndExecute($this->connection, $sql, $params);
        }



        /**
         * {@inheritDoc}
         */
        public function prepareAndExecute ($connection, string $query, array $params = []) : self
        {
            $connection->prepare($query)->execute($params);
            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function getConnection() : PDO
        {
            return $this->connection;
        }
    }