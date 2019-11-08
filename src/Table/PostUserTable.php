<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\PostUser;


    /**
     * Manage the queries of the post_user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostUserTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_user";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = PostUser::class;
    }