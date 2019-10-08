<?php

    namespace jeyofdev\php\blog\Database;


    use PDO;


    /**
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    Class Query
    {
        /**
         * Set a query and execute it
         *
         * @return void
         */
        public static function prepareAndExecute (PDO $connection, string $query) : void
        {
            $connection->prepare($query)->execute();
        }
    }