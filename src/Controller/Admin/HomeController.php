<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;


    /**
     * Manage the controller of the admin home page
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class HomeController extends AbstractController
    {
        public function index () : void
        {
            $title = App::getInstance()
                ->setTitle("Administration of the blog")
                ->getTitle();

            $this->render("admin.home.index", $this->router, compact("title"));
        }
    }