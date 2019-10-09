<?php

    namespace jeyofdev\php\blog\Database;


    /**
     * Manage the creation and deletion of a database
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Database extends AbstractConnection implements DatabaseInterface
    {
        public function __construct (string $db_host, string $db_user, string $db_password, string $db_name)
        {
            $this->db_host = $db_host;
            $this->db_user = $db_user;
            $this->db_password = $db_password;
            $this->db_name = $db_name;

            $this->connection = $this->getConnection();
        }



        /**
         * {@inheritDoc}
         */
        public function create () : self
        {
            $sql = "CREATE DATABASE IF NOT EXISTS " . $this->db_name . " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
            Query::prepareAndExecute($this->connection, $sql);

            $this->db_name = $this->db_name;

            return $this;
        }



        /**
         * {@inheritDoc}
         */
        public function drop () : self
        {
            $sql = "DROP DATABASE " . $this->db_name;
            Query::prepareAndExecute($this->connection, $sql);

            return $this;
        }
    }