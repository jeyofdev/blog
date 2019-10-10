<?php

    namespace jeyofdev\php\blog\Manager;


    use PDO;


    /**
     * manage PDO methods
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface ManagerInterface
    {
        /**
         * Get the connection pdo
         * 
         * @return PDO
         */
        public function getConnection();



        /**
         * Set a query and execute it
         *
         * @return self
         */
        public function prepareAndExecute (PDO $connection, string $query, array $params = []);
    }