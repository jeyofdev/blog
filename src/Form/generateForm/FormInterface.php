<?php

    namespace jeyofdev\php\blog\Form\generateForm;


    /**
     * Build the form related to posts
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface FormInterface
    {
        /**
         * Build the form
         *
         * @return string
         */
        public function build ();



        /**
         * Extract the elements of the form
         *
         * @return array
         */
        public function extract ();
    }