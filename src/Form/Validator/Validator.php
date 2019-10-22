<?php

    namespace jeyofdev\php\blog\Form\Validator;


    use Valitron\Validator as ValitronValidator;


    /**
     *  Validator
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Validator extends ValitronValidator
    {
        /**
         * Validate a form field 
         * and set the error message if it contains errors
         *
         * @return string
         */
        protected function checkAndSetLabel($field, $message, $params) : string
        {
            return str_replace('{field}', '', $message);
        }
    }