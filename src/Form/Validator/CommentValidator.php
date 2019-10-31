<?php

    namespace jeyofdev\php\blog\Form\Validator;


    /**
     * Validate the form related to comments
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentValidator extends AbstractValidator
    {
        public function __construct(string $lang, array $datas)
        {
            parent::__construct($datas);

            $this->validator::lang($lang);

            $this->validator->rule("required", ["username", "content"]);
            $this->validator->rule("lengthBetween", "username", 3, 200);
            $this->validator->rule('lengthBetween', 'content', 5, 500);
        }
    }