<?php

    namespace jeyofdev\php\blog\Exception;


    use RuntimeException;


    class ExecuteQueryFailedException extends RuntimeException
    {
        /**
         * @return self
         */
        public function createHasFailed (string $tableName) : self
        {
            $this->message = "An error occurred while creating a record in the $tableName table";
            return $this;
        }



        /**
         * @return self
         */
        public function updateHasFailed (string $id, string $tableName) : self
        {
            $this->message = "An error occurred while updating the record with the id $id in the table $tableName";
            return $this;
        }



        /**
         * @return self
         */
        public function deleteHasFailed (string $id, string $tableName) : self
        {
            $this->message = "An error occurred while deleting the record with the id $id in the table $tableName";
            return $this;
        }
    }