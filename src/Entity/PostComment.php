<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the post_comment table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostComment extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $post_id;
        protected $comment_id;


        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_comment";



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
            $this->setColumnsWithOptions("comment_id", "int", 10, false, true, false, null, true, [
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
         * Get the value of comment_id
         */ 
        public function getComment_id () : ?int
        {
            return $this->comment_id;
        }



        /**
         * Set the value of comment_id
         *
         * @return  self
         */ 
        public function setComment_id ($comment_id) : self
        {
            $this->comment_id = $comment_id;
            return $this;
        }
    }