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
         * The path of views
         * 
         * @var string
         */
        private $viewPath;



        /**
         * @var AltoRouter
         */
        private $router;



        public function __construct (string $viewPath)
        {
            $this->viewPath = $viewPath;
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
         * Load the view that matches the requested URL
         *
         * @return self
         */
        public function run () : self
        {
            $match = $this->router->match();
            $view = $match['target'];

            ob_start();
            require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
            $content = ob_get_clean();
            require $this->viewPath . DIRECTORY_SEPARATOR . 'layout/default.php';

            return $this;
        }
    }