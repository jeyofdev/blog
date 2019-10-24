<?php

    namespace jeyofdev\php\blog\Form\Validator;


    use jeyofdev\php\blog\Table\PostTable;


    /**
     * Validate the forms related to posts
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostValidator extends AbstractValidator
    {
        public function __construct(string $lang, array $datas, PostTable $tablePost, ?int $postID = null)
        {
            parent::__construct($datas);

            $this->validator::lang($lang);

            $this->validator->rule("required", ["name", "slug", "content"]);
            $this->validator->rule("lengthBetween", ["name", "slug"], 3, 200);
            $this->validator->rule('lengthBetween', 'content', 10, 10000);
            $this->validator->rule(function ($field, $value) use ($tablePost, $postID) {
                $params = [$field => $value];
                return !$tablePost->exists($params, $postID);
            }, ["name", "slug"], "This value is already used");
        }
    }