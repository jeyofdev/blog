<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\CommentUser;


    /**
     * Manage the queries of the comment_user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentUserTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "comment_user";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = CommentUser::class;
    }