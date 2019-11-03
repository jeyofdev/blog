<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the post_image table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostImage extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $post_id;
        protected $image_id;


        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_image";



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
            $this->setColumnsWithOptions("image_id", "int", 10, false, true, false, null, true, [
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
         * Get the value of image_id
         */ 
        public function getImage_id () : ?int
        {
            return $this->image_id;
        }



        /**
         * Set the value of image_id
         *
         * @return  self
         */ 
        public function setImage_id ($image_id) : self
        {
            $this->image_id = $image_id;
            return $this;
        }
    }