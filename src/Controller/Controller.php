<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\Router\Router;


    /**
     * Manage the controller
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class controller
    {
        /**
         * The path of views
         * 
         * @var string
         */
        private $viewPath = VIEW_PATH;



        /**
         * Send the datas to the view
         *
         * @return void
         */
        public function render (string $view, array $datas = []) : void
        {
            ob_start();
            extract($datas);
            require $this->getViewPath() . DIRECTORY_SEPARATOR . str_replace(".", "/", $view) . '.php';
            $content = ob_get_clean();
            require $this->getViewPath() . DIRECTORY_SEPARATOR . 'layout/default.php';
        }



        /**
         * Get the path of views
         *
         * @return void
         */
        public function getViewPath()
        {
            return $this->viewPath;
        }
    }