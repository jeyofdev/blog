<?php

    namespace jeyofdev\php\blog\Form\Validator;


    use jeyofdev\php\blog\Table\CategoryTable;


    /**
     * Validate the forms related to categories
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CategoryValidator extends AbstractValidator
    {
        public function __construct(string $lang, array $datas, CategoryTable $tableCategory, ?int $categoryID = null)
        {
            parent::__construct($datas);

            $this->validator::lang($lang);

            $this->validator->rule("required", ["name", "slug"]);
            $this->validator->rule("lengthBetween", ["name", "slug"], 3, 200);
            $this->validator->rule(function ($field, $value) use ($tableCategory, $categoryID) {
                $params = [$field => $value];
                return !$tableCategory->exists($params, $categoryID);
            }, ["name", "slug"], "This value is already used");
        }
    }