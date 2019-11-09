<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the comment table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Comment extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $id;
        protected $content;



        /**
         * The associated user to the comment
         *
         * @var User
         */
        private $user;



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("id", "int", 10, false, true, true, null, true);
            $this->setColumnsWithOptions("content", "text", 650000);

            return $this;
        }



        /**
         * Get the value of id
         */ 
        public function getId() : ?int
        {
            return $this->id;
        }



        /**
         * Set the value of id
         *
         * @param int|string $id
         * @return self
         */ 
        public function setId($id) : self
        {
            $this->id = $id;
            return $this;
        }



        /**
         * Get the value of content
         */ 
        public function getContent() : ?string
        {
            return $this->content;
        }



        /**
         * Set the value of content
         *
         * @return  self
         */ 
        public function setContent(string $content) : self
        {
            $this->content = $content;
            return $this;
        }



        /**
         * Get the associated user to the comment
         *
         * @return User
         */
        public function getUser ()
        {
            return $this->user;
        }



        /**
         * Add a user on the comment
         *
         * @param User $user
         * @return self
         */
        public function addUser (User $user) : self
        {
            $this->user = $user;
            return $this;
        }
    }