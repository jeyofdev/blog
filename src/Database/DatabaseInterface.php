<?php

    namespace jeyofdev\php\blog\Database;


    /**
     * Manage the creation and deletion of a database
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface DatabaseInterface
    {
        /**
         * Create a database
         *
         * @return self
         */
        public function create ();



        /**
         * Drop a database
         *
         * @return self
         */
        public function drop ();
    }