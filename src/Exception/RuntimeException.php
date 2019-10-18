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
    }