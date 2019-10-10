<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the fixtures
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface FixturesInterface
    {
        /**
         * Add the fixtures in the database
         *
         * @return self
         */
        public function add ();
    }