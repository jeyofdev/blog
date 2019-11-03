<?php

    namespace jeyofdev\php\blog\Form\generateForm;


    /**
     * Set the elements of the form with bootstrap
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractBuilderBootstrapForm extends AbstractBuilderForm
    {
        /**
         * {@inheritDoc}
         */
        public function input (string $type, string $name, ?string $label, array $options, array $surround = [], ?string $errorClass = "invalid-feedback") : self
        {
            $bootstrapClass = $this->SetBootstrapClass($options, $surround);
            $options = $bootstrapClass[0];
            $surround = $bootstrapClass[1];
            
            return parent::input($type, $name, $label, $options, $surround, $errorClass);
        }



        /**
         * Set the input of type file
         * 
         * @return self
         */
        public function file (string $name, ?string $label, array $options = [], array $surround = [], ?string $errorClass = "invalid-feedback") : self
        {
            $bootstrapClass = $this->SetBootstrapClass($options, $surround, "file");
            $options = $bootstrapClass[0];
            $surround = $bootstrapClass[1];
            
            return parent::input("file", $name, $label, $options, $surround, $errorClass);
        }



        /**
         * {@inheritDoc}
         */
        public function textarea (string $name, string $label, array $options, array $surround = [], ?string $errorClass = "invalid-feedback") : self
        {
            $bootstrapClass = $this->SetBootstrapClass($options, $surround);
            $options = $bootstrapClass[0];
            $surround = $bootstrapClass[1];

            return parent::textarea($name, $label, $options, $surround, $errorClass);
        }



        /**
         * {@inheritDoc}
         */
        public function select (string $name, string $label, array $options = [], array $optionsSelect, array $surround = []) : self
        {
            $bootstrapClass = $this->SetBootstrapClass($options, $surround);
            $options = $bootstrapClass[0];
            $surround = $bootstrapClass[1];

            return parent::select ($name, $label, $options, $optionsSelect, $surround);
        }



        /**
         * {@inheritDoc}
         */
        public function submit (string $label, ?string $class = "btn btn-primary") : self
        {
            return parent::submit($label, $class);
        }



        /**
         * {@inheritDoc}
         */
        public function reset (string $label, ?string $class = "btn btn-danger") : self
        {
            return parent::reset($label, $class);
        }



        /**
         * {@inheritDoc}
         */
        protected function getErrorFeddback (string $key, ?string $errorClass = "invalid-feedback") : ?string
        {
            return parent::getErrorFeddback($key, $errorClass);
        }



        /**
         * Initialize the class attributes with bootstrap
         *
         * @return array
         */
        private function SetBootstrapClass (array $options, array $surround, ?string $bootstrapClassSuffix = null) : array
        {
            $bootstrapClass = "form-control";
            $bootstrapClass .= !is_null($bootstrapClassSuffix) ? "-$bootstrapClassSuffix" : null;

            if (array_key_exists("class", $options)) {
                $options["class"] = $bootstrapClass . " " . $options["class"];
            } else {
                $options["class"] = $bootstrapClass;
            }

            if (!empty($surround)) {
                if (array_key_exists("class", $surround)) {
                    $surround["class"] = "form-group " . $surround["class"];
                } else {
                    $surround["class"] = "form-group";
                }
            }

            return [$options, $surround];
        }
    }