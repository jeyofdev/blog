<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\User;


    /**
     * Manage the queries of the user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class UserTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "user";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = User::class;
    }