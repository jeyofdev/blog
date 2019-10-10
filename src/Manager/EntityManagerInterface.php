<?php

    namespace jeyofdev\php\blog\Manager;


    /**
     * Set the sql request
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface EntityManagerInterface extends ManagerInterface
    {
        /**
         * Add a new record in the database
         *
         * @param object $entity 
         * @return void
         */
        public function insert($entity);
    }