<?php

    namespace jeyofdev\php\blog\Core;


    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Session\Session;
    use jeyofdev\php\blog\Url;


    /**
     * Manage user authentication
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Auth
    {
        /**
         * Check that the user is logged in
         *
         * @param Router $router
         * @return void
         */
        public static function isConnect (Router $router) : void
        {
            $session = new Session();

            if (!$session->exist("auth")) {
                $url = $router->url("login") . "?forbidden=1";
                Url::redirect(301, $url);
            }
        }



        /**
         * Check that the user connected with the admin role
         *
         * @param Session $session
         * @return boolean
         */
        public static function isAdmin (Session $session)
        {
            return ($session->read("role") === "admin") ? true : false;
        }
    }