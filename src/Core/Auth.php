<?php

    namespace jeyofdev\php\blog\Core;


    use jeyofdev\php\blog\Router\Router;
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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION["auth"])) {
                $url = $router->url("login") . "?forbidden=1";
                Url::redirect(301, $url);
            }
        }
    }