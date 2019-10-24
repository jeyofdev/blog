<?php

    namespace jeyofdev\php\blog\Form;


    use jeyofdev\php\blog\Form\generateForm\AbstractBuilderBootstrapForm;
    use jeyofdev\php\blog\Form\generateForm\FormInterface;


    /**
     * Build the form related to categories
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CategoryForm extends AbstractBuilderBootstrapForm implements FormInterface
    {
        /**
         * {@inheritDoc}
         */
        public function build (string $labelSubmit, array $categories = [], $createdAt = false, $updatedAt = false) : string
        {
            $this
                ->formStart("#", "post", "my-5")
                ->input("text", "name", "Title :", [], ["tag" => "div"])
                ->input("text", "slug", "Slug :", [], ["tag" => "div"])
                ->submit($labelSubmit)
                ->reset("Reset")
                ->formEnd();

            return implode("\n", $this->extract());
        }



        /**
         * {@inheritDoc}
         */
        public function extract () : array
        {
            extract($this->form);

            $buttons = implode("\n", $buttons);
            $fields = implode("\n", $fields);

            $this->form = [
                "start" => $start,
                "fields" => $fields,
                "buttons" => $buttons,
                "end" => $end,
            ];

            return $this->form;
        }
    }