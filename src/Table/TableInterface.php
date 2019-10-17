<?php

    namespace jeyofdev\php\blog\Table;


    use PDOStatement;


    /**
     * Manage the execution of a query
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface TableInterface
    {
        /**
         * Executes an SQL statement
         *
         * @return PDOStatement
         */
        public function query (string $sql, int $fetchMode = PDO::FETCH_CLASS);



        /**
         * Prepares a statement
         *
         * @return PDOStatement
         */
        public function prepare (string $sql, array $params = [], int $fetchMode = PDO::FETCH_CLASS);



        /**
         * Get the first result of a query
         *
         * @return mixed
         */
        public function fetch ();



        /**
         * Get all the results of a query
         *
         * @return array
         */
        public function fetchAll ();



        /**
         * Set the fetch mode 
         *
         * @return void
         */
        public function setFetchMode (int $fetchMode);
    }