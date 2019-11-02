<?php

    namespace jeyofdev\php\blog\Form\Validator;


    /**
     * Validation of the forms related to users
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    
    class UserValidator extends AbstractValidator
    {
        public function __construct(string $lang, array $datas)
        {
            parent::__construct($datas);

            $this->validator::lang($lang);

            $this->validator->rule("required", ["username", "password"]);
            $this->validator->rule("lengthBetween", "username", 3, 50);
        }
    }