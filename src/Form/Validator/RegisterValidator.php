<?php

    namespace jeyofdev\php\blog\Form\Validator;


    use jeyofdev\php\blog\Table\UserTable;


    /**
     * Validation of the registration form
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    
    class RegisterValidator extends AbstractValidator
    {
        public function __construct(string $lang, array $datas, UserTable $tableUser)
        {
            parent::__construct($datas);

            $this->validator::lang($lang);

            $this->validator->rule("required", ["username", "password", "passwordConfirm"]);
            $this->validator->rule("lengthBetween", "username", 3, 50);
            $this->validator->rule("lengthBetween", ["password", "passwordConfirm"], 5, 255);
            $this->validator->rule("equals", "password", "passwordConfirm");
            $this->validator->rule(function ($field, $value) use ($tableUser) {
                $params = [$field => $value];
                return !$tableUser->exists($params);
            }, "username", "This username is already used");
        }
    }