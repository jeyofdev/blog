<?php

    namespace jeyofdev\php\blog\Form;


    use jeyofdev\php\blog\Form\generateForm\AbstractBuilderBootstrapForm;
use jeyofdev\php\blog\Form\generateForm\AbstractBuilderForm;

class PostForm extends AbstractBuilderBootstrapForm
    {
        public function generate ()
        {
            $this
                ->formStart("#", "post", "my-5")
                ->input("text", "name", "Title :", [], ["tag" => "div"])
                ->input("text", "slug", "Slug :", [], ["tag" => "div"])
                ->textarea("content", "Content :", ["rows" => 8], ["tag" => "div"])
                ->submit("Update")
                ->reset("Reset")
                ->formEnd();

            // $this
            //     ->formStart("#", "post", "my-5")
            //     ->input("text", "name", "Title :", ["required" => true], ["div", "form-group"], "invalid-feedback")
            //     ->input("text", "slug", "Slug :", ["class" => "form-control"], ["div", "form-group"], "invalid-feedback")
            //     ->textarea("content", "Content :", ["class" => "form-control", "rows" => 8], ["div", "form-group"], "invalid-feedback")
            //     ->submit("Update")
            //     ->reset("Reset")
            //     ->formEnd();
        

            // dd($this->form);

            return implode("\n", $this->extract());
        }



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