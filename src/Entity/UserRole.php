<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the user_role table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class UserRole extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $user_id;
        protected $role_id;


        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "user_role";



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("user_id", "int", 10, false, true, false, null, true, [
                "delete" => "CASCADE",
                "update" => "RESTRICT"
            ]);
            $this->setColumnsWithOptions("role_id", "int", 10, false, true, false, null, true, [
                "delete" => "CASCADE",
                "update" => "RESTRICT"
            ]);

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



        /**
         * Get the value of role_id
         */ 
        public function getRole_id () : ?int
        {
            return $this->role_id;
        }



        /**
         * Set the value of role_id
         *
         * @return  self
         */ 
        public function setRole_id ($role_id) : self
        {
            $this->role_id = $role_id;
            return $this;
        }
    }