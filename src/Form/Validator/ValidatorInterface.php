<?php

    namespace jeyofdev\php\blog\Form\Validator;


    /**
     *  Check that the datas in a form are valid
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface ValidatorInterface
    {
        /**
         * check that the form has submit
         *
         * @return bool
         */
        public function isSubmit ();



        /**
         * Check that the form does not contain any errors
         *
         * @return bool
         */
        public function isValid ();



        /**
         * Get the form errors
         *
         * @return array
         */
        public function getErrors ();
    }