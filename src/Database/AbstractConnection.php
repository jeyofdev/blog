<?php

    namespace jeyofdev\php\blog\Database;


    use PDO;


    /**
     * Manage the PDO connection
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractConnection
    {
        /**
         * The parameters of the connection
         *
         * @var string
         */
        protected $db_host;
        protected $db_user;
        protected $db_password;
        protected $db_name;



        /**
         * @var PDO
         */
        protected $connection;



        /**
         * PDO options
         */
        const PDO_OPTIONS = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];



        /**
         * Get the connection pdo
         *
         * @return PDO
         */
        public function getConnection(?string $db_name = null) : PDO
        {
            try {
                if (!is_null($this->setConnection($db_name))) {
                    $this->db_name = $db_name;
                    $this->setConnection($db_name);
                }
            } catch(PDOException $e){
                throw new PDOException("the connection failed, the error returned is : " . $e->getMessage());
            }
            
            return $this->connection;
        }



        /**
         * Set the connection pdo
         *
         * @return void
         */
        protected function setConnection (?string $db_name = null) : void
        {
            if ($db_name === null) {
                $this->connection = new PDO("mysql:host=" . $this->db_host, $this->db_user, $this->db_password , self::PDO_OPTIONS);
            } else {
                $this->db_name = $db_name;
                $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name . ";", $this->db_user, $this->db_password , self::PDO_OPTIONS);
            }
        }



        /**
         * Get the name of the database
         * 
         * @return string
         */
        public function getDb_name() : string
        {
            return $this->db_name;
        }
    }