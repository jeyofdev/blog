<?php

    namespace jeyofdev\php\blog;


    use InvalidArgumentException;


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
        public static function redirect () : void
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
                throw new InvalidArgumentException("The $name parameter in the url must be a positive integer");
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
                throw new InvalidArgumentException("The $name parameter in the url must be an integer");
            }

            return (int)$_GET[$name];
        }
    }