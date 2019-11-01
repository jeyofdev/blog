<?php

    namespace jeyofdev\php\blog\Controller\Error;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;


    /**
     * Manage the controller of the errors
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class ErrorController extends AbstractController
    {
        /**
         * 404
         *
         * @return void
         */
        public function notFound () : void
        {
            header("HTTP/1.1 404 Not Found");

            // flash message
            $this->session->setFlash("The page you want to access is not found or does not exist", "danger", "my-5 text-center");
            $flash = $this->session->generateFlash();

            $title = App::getInstance()
                ->setTitle("Error 404")
                ->getTitle();

            $this->render("error/404", $this->router, $this->session, compact("title", "flash"));
        }
    }