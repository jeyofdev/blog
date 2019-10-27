<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the user table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class User extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $id;
        protected $username;
        protected $password;



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("id", "int", 10, false, true, true, null, true);
            $this->setColumnsWithOptions("username", "varchar", 50);
            $this->setColumnsWithOptions("password", "varchar", 255);

            return $this;
        }



        /**
         * Get the value of id
         * 
         * @return int|null
         */ 
        public function getId () : ?int
        {
            return $this->id;
        }



        /**
         * Set the value of id
         *
         * @return self
         */ 
        public function setId (int $id) : self
        {
            $this->id = $id;
            return $this;
        }



        /**
         * Get the value of username
         * 
         * @return string|null
         */ 
        public function getUsername () : ?string
        {
            return $this->username;
        }



        /**
         * Set the value of username
         *
         * @return self
         */ 
        public function setUsername (string $username) : self
        {
            $this->username = $username;
            return $this;
        }



        /**
         * Get the value of password
         * 
         * @return string|null
         */ 
        public function getPassword () : ?string
        {
            return $this->password;
        }



        /**
         * Set the value of password
         *
         * @return self
         */ 
        public function setPassword(string $password) : self
        {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
            return $this;
        }
    }