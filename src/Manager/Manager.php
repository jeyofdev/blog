<?php

    namespace jeyofdev\php\blog\Manager;


    use jeyofdev\php\blog\Database\Database;
    use PDO;


    /**
     * Set the sql request
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Manager implements ManagerInterface
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
            $this->connection = $this->database->getConnection();
        }



        /**
         * {@inheritDoc}
         */
        public function prepareAndExecute (PDO $connection, string $query, array $params = []) : self
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