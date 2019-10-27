<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Session\Session;


    /**
     * Manage the controller
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    interface ControllerInterface
    {
        /**
         * Send the datas to the view
         *
         * @return void
         */
        public function render (string $view, Router $router, Session $session, array $datas = []);



        /**
         * Get the path of views
         *
         * @return string
         */
        public function getViewPath();



        /**
         * Check that the datas exists
         *
         * @param Post|Category $table 
         * @return void
         */
        public function exists ($table, string $tableName, int $id);



        /**
         * Check that the slug of the url corresponds to the slug of the current table
         *
         * @param Post|Category $table
         * @return void
         */
        public function checkSlugMatch (Router $router, $table, string $slug, int $id);
    }