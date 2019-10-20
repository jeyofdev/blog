<?php

    namespace jeyofdev\php\blog\Exception;


    use RuntimeException;


    class ExecuteQueryFailedException extends RuntimeException
    {
        /**
         * @return self
         */
        public function deleteHasFailed (string $id, string $tableName) : self
        {
            $this->message = "An error occurred while deleting the record with the id $id in the table $tableName";
            return $this;
        }
    }