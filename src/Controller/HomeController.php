<?php

    namespace jeyofdev\php\blog\Controller;


    /**
     * Manage the controller of the home page
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class HomeController extends Controller
    {
        /**
         * Set the datas of the home page
         *
         * @return void
         */
        public function index () : void
        {
            $content = "ceci est le controller de la home page";
            $this->render('home.index', compact('content'));
        }
    }