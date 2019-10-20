<?php

    namespace jeyofdev\php\blog\Router;


    /**
     * Manage the router
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Router
    {
        /**
         * @var AltoRouter
         */
        private $router;



        public function __construct ()
        {
            $this->router = new AltoRouter();
        }



        /**
         * Get the indexed route by the GET method
         *
         * @param  string      $url  The url called
         * @param  string      $view The view corresponding to the url
         * @param  string|null $name The name of the route
         * @return self
         */
        public function get (string $url, string $view, ?string $name = null) : self
        {
            $this->router->map('GET', $url, $view, $name);
            return $this;
        }



        /**
         * Get the indexed route by the POST method
         *
         * @param  string      $url  The url called
         * @param  string      $view The view corresponding to the url
         * @param  string|null $name The name of the route
         * @return self
         */
        public function post (string $url, string $view, ?string $name = null) : self
        {
            $this->router->map('post', $url, $view, $name);
            return $this;
        }



        /**
         * Get the indexed route by the GET or POST method
         *
         * @param  string      $url  The url called
         * @param  string      $view The view corresponding to the url
         * @param  string|null $name The name of the route
         * @return self
         */
        public function match (string $url, string $view, ?string $name = null) : self
        {
            $this->router->map('get|post', $url, $view, $name);
            return $this;
        }



        /**
         * Generate a route
         *
         * @param  string $name The name of the route
         * @param  string $name The params of the route
         * @return void
         */
        public function url (string $name, array $params = [])
        {
            return $this->router->generate($name, $params);
        }



        /**
         * Get the router
         *
         * @return void
         */
        public function getRouter() : AltoRouter
        {
            return $this->router;
        }



        /**
         * Get the url parameters
         *
         * @return void
         */
        public function getParams()
        {
            return $this->router->match()["params"];
        }
    }