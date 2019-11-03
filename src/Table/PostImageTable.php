<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\PostImage;

/**
     * Manage the queries of the post_image table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostImageTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_image";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = PostImage::class;
    }