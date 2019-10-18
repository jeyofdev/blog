<?php

    namespace jeyofdev\php\blog\Exception;


    use Exception;


    /**
     * handle errors de type 'not Found'
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class NotFoundException extends Exception
    {
        /**
         * @return self
         */
        public function itemNotFound (string $tableName, int $id) : self
        {
            $this->message = "No $tableName match with id : $id";
            return $this;
        }



        /**
         * @return self
         */
        public function pageNotFound () : self
        {
            $this->message = "This page does not exist";
            return $this;
        }
    }
