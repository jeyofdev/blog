<?php

    namespace jeyofdev\php\blog\Entity;


    /**
     * Manage the tables in the database
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface EntityInterface
    {
        /**
         * Add a table
         *
         * @return self
         */
        public function addTable ();



        /**
         * Drop a table
         *
         * @return self
         */
        public function dropTable ();



        /**
         * Empty a table
         *
         * @return self
         */
        public function emptyTable ();



        /**
         * Get the columns
         *
         * @return array
         */
        public function getColumns();



        /**
         * Set the columns
         *
         * @return self
         */
        public function setColumns($entity);



        /**
        * Set the value of columnsWithOptions
        *
        * @return self
        */
        public function setColumnsWithOptions(string $name, string $type, ?int $option = null, bool $isNull = false, bool $unsigned = false, bool $autoIncrement = false, $defaultValue = null, $primaryKey = false);
    }