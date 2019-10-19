<?php

    namespace jeyofdev\php\blog\Exception;


    use RuntimeException as GlobalRuntimeException;


    /**
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class RuntimeException extends GlobalRuntimeException
    {
        /**
         * @return self
         */
        public function propertyValueIsNull ($className, string $propertyName) : self
        {
            $this->message = "The class $className does not have a $propertyName property ";
            return $this;
        }



        /**
         * @return self
         */
        public function columnNotExistInDatabase(string $value) : self
        {
            $this->message = "The column '$value' does not exist in the database";
            return $this;
        }



        /**
         * @return self
         */
        public function valueNotAllowed(string $value, string $type) : self
        {
            $this->message = "The $type '$value' is not allowed";
            return $this;
        }
    }