<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;


    /**
     * Manage the controller of the home page
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class HomeController extends AbstractController
    {
        /**
         * Set the datas of the home page
         *
         * @return void
         */
        public function index () : void
        {
            $title = App::getInstance()->setTitle("Home")->getTitle();
            $this->render('home.index', $this->router, $this->session, compact("title"));
        }
    }