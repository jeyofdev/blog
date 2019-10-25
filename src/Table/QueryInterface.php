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
         * Get all rows from a table
         *
         * @return array
         */
        public function findAll (int $fetchMode = PDO::FETCH_CLASS);



        /**
         * Get all the rows of a table and sort the results
         *
         * @return array
         */
        public function findAllBy (?string $orderBy = null, string $direction = "ASC", ?int $limit = null, ?int $offset = null, int $fetchMode = PDO::FETCH_CLASS);



        /**
         * Add a new row in the table
         *
         * @return self
         */
        public function create (array $params);



        /**
         * Update a row from a table
         *
         * @return self
         */
        public function update (array $params, array $where);



        /**
         * Delete a row from a table
         *
         * @return self
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