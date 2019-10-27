<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the post_user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostUser extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $post_id;
        protected $user_id;


        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_user";



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
            $this->setColumnsWithOptions("user_id", "int", 10, false, true, false, null, true, [
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
         * Get the value of user_id
         */ 
        public function getUser_id () : ?int
        {
            return $this->user_id;
        }



        /**
         * Set the value of user_id
         *
         * @return  self
         */ 
        public function setUser_id ($user_id) : self
        {
            $this->user_id = $user_id;
            return $this;
        }
    }