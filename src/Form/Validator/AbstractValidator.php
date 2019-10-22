<?php

    namespace jeyofdev\php\blog\Form\Validator;


    /**
     *  Check that the datas in a form are valid
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractValidator implements ValidatorInterface
    {
        /**
         * The datas sent to the form
         *
         * @var array
         */
        protected $datas = [];



        /**
         * @var Validator
         */
        protected $validator;



        public function __construct (array $datas)
        {
            $this->datas = $datas;
            $this->validator = new Validator($datas);
        }



        /**
         * {@inheritDoc}
         */
        public function isSubmit () : bool
        {
            if (!empty($this->datas)) {
                return true;
            }

            return false;
        }



        /**
         * {@inheritDoc}
         */
        public function isValid () : bool
        {
            return $this->validator->validate();
        }



        /**
         * {@inheritDoc}
         */
        public function getErrors () : array
        {
            return $this->validator->errors();
        }
    }

