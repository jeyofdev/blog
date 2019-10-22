<?php

    namespace jeyofdev\php\blog\Form;


    use jeyofdev\php\blog\Form\generateForm\AbstractBuilderBootstrapForm;
    use jeyofdev\php\blog\Form\generateForm\FormInterface;


    /**
     * Build the form related to posts
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostForm extends AbstractBuilderBootstrapForm implements FormInterface
    {
        /**
         * {@inheritDoc}
         */
        public function build () : string
        {
            $this
                ->formStart("#", "post", "my-5")
                ->input("text", "name", "Title :", [], ["tag" => "div"])
                ->input("text", "slug", "Slug :", [], ["tag" => "div"])
                ->textarea("content", "Content :", ["rows" => 8], ["tag" => "div"])
                ->input("text", "created_at", "Creation date :", ["disabled" => true], ["tag" => "div"])
                ->input("text", "updated_at", "Last modified date :", ["disabled" => true], ["tag" => "div"])
                ->submit("Update")
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