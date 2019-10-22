<?php

    namespace jeyofdev\php\blog\Table;


    /**
     * Allows to define and execute a query
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface QueryInterface
    {
        /**
         * Get a row from a table
         *
         * @return mixed
         */
        public function find (array $params, $fetchMode = PDO::FETCH_CLASS);



        /**
         * Get all the rows from a table
         *
         * @return array
         */
        public function findAllBy (?string $orderBy = null, string $direction = "ASC", ?int $limit = null, ?int $offset = null, int $fetchMode = PDO::FETCH_CLASS);



        /**
         * Update a row from a table
         *
         * @return void
         */
        public function update (array $params, array $where);



        /**
         * Delete a row from a table
         *
         * @return void
         */
        public function delete (array $params);



        /**
         * Check that a value is already present in a column of a table
         *
         * @param integer|null $except The rows to be exluded from the verification
         * @return bool
         */
        public function exists (array $params, ?int $except = null);



        /**
         * Get the total number of rows in a table
         *
         * @return int
         */
        public function countAll (string $column, int $fetchMode = PDO::FETCH_NUM);
    }