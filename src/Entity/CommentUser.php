<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the comment_user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentUser extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $comment_id;
        protected $user_id;


        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "comment_user";



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("comment_id", "int", 10, false, true, false, null, true, [
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