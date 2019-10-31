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
        protected $username;
        protected $content;



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("id", "int", 10, false, true, true, null, true);
            $this->setColumnsWithOptions("username", "varchar", 50);
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
         * @return self
         */ 
        public function setId(int $id) : self
        {
            $this->id = $id;
            return $this;
        }



        /**
         * Get the value of username
         */ 
        public function getUsername() : ?string
        {
            return $this->username;
        }



        /**
         * Set the value of username
         *
         * @return  self
         */ 
        public function setUsername(string $username) : self
        {
            $this->username = $username;
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
    }