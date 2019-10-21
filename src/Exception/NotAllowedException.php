<?php

    namespace jeyofdev\php\blog\Exception;


    use RuntimeException as GlobalRuntimeException;


    /**
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class NotAllowedException extends GlobalRuntimeException
    {
        public function __construct (string $value, string $attribute)
        {
            $this->message = "The $attribute '$value' is not allowed";
            return $this->message;
        }
    }