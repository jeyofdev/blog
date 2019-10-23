<?php

    namespace jeyofdev\php\blog;


    use jeyofdev\php\blog\Exception\InvalidArgumentException;
    use jeyofdev\php\blog\Exception\RuntimeException;


    /**
     * Manage the url
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Url
    {
        /**
         * Redirect to the 1st page of the blog
         *
         * @return void
         */
        public static function redirectToHome () : void
        {
            if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] === "/blog/") {
                if (isset($_GET["page"]) && $_GET["page"] === "1") {
                    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
                    $get = $_GET;
            
                    unset($get['page']);
                    $query = http_build_query($get);
                    
                    if (!empty($query)) {
                        $uri = $uri . '?' . $query;
                    }
            
                    http_response_code(301);
                    header('Location: ' . $uri);
            
                    exit();
                }
            }
        }



        /**
         * Check that the value of a parameter in the URL is a positive integer
         *
         * @param  string $name the name of the parameter
         * @param  integer|null $default The default value
         * @return integer|null
         */
        public static function getPositiveInt (string $name, ?int $default = null) : ?int
        {
            $param = self::getInt($name, $default);

            if ($param !== null && $param <= 0) {
                throw new RuntimeException("The parameter url '$name' must be a positive integer");
            }

            return $param;
        }



        /**
         * Check that the value of a parameter of the URL is of integer type
         *
         * @param  string $name the name of the parameter
         * @param  integer|null $default The default value
         * @return integer|null
         */
        public static function getInt (string $name, ?int $default = null) : ?int 
        {
            if (!isset($_GET[$name])) return $default;
            if ($_GET[$name] === '0') return 0;

            if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
                throw new InvalidArgumentException("The parameter url '$name' must be an integer");
            }

            return (int)$_GET[$name];
        }



        /**
         * Execute a redirection
         *
         * @param int $code http response code (301, 404, 200...)
         * @param string|void $url The redirection url
         * @return void
         */
        public static function redirect (int $code, $url) : void
        {
            http_response_code($code);
            header("Location: " . $url);
            exit();
        }
    }