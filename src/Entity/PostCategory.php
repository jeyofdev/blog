<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the post_category table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostCategory extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $post_id;
        protected $category_id;


        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_category";



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("post_id", "int", 10, false, true, false, null, true, [
                "delete" => "CASCADE",
                "update" => "RESTRICT"
            ]);
            $this->setColumnsWithOptions("category_id", "int", 10, false, true, false, null, true, [
                "delete" => "CASCADE",
                "update" => "RESTRICT"
            ]);

            return $this;
        }



        /**
         * Get the value of post_id
         */ 
        public function getPost_id () : ?int
        {
            return $this->post_id;
        }



        /**
         * Set the value of post_id
         *
         * @return  self
         */ 
        public function setPost_id ($post_id) : self
        {
            $this->post_id = $post_id;
            return $this;
        }



        /**
         * Get the value of category_id
         */ 
        public function getCategory_id () : ?int
        {
            return $this->category_id;
        }



        /**
         * Set the value of category_id
         *
         * @return  self
         */ 
        public function setCategory_id ($category_id) : self
        {
            $this->category_id = $category_id;
            return $this;
        }
    }