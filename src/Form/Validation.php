<?php

    namespace jeyofdev\php\blog\Form;


    /**
     * Validate the datas of a form
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Validation
    {
        /**
         * The form errors
         *
         * @var array
         */
        private $errors = [];



        public function __construct(array $errors = [])
        {
            $this->errors = $errors;
        }



        /**
         * Check if the form is submit
         *
         * @return bool
         */
        public function checkFormIsSubmit () : bool
        {
            if (!empty($_POST)) {
                return true;
            }

            return false;
        }



        /**
         * Check that a form is valid
         *
         * @return boolean
         */
        public function checkFormIsValid () : bool
        {
            if (empty($this->errors)) {
                return true;
            }

            return false;
        }



        /**
         * Check that a form field is not empty
         *
         * @param string $name The name attribute of the field
         * @return self
         */
        public function checkNotEmpty (string $name) : self
        {
            if (empty($_POST[$name])) {
                $this->errors[$name][] = "The $name field can not be empty.";
            }

            return $this;
        }



        /**
         * Check that a form field has a sufficient number of characters
         *
         * @param string $name The name attribute of the field
         * @param integer $min The minimum number of characters
         * @return self
         */
        public function checkMin (string $name, int $min) : self
        {
            if (mb_strlen($_POST[$name]) < $min) {
                $this->errors[$name][] = "The $name field must contain at least $min characters.";
            }

            return $this;
        }



        /**
         *  Get the value of errors
         *
         * @return array
         */
        public function getErrors() : array
        {
            return $this->errors;
        }
    }