<?php

    namespace jeyofdev\php\blog;


    /**
     * Global class of the App
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class App
    {
        /**
         * @var App
         */
        private static $_instance;



        /**
         * The title of the current page
         * 
         * @var string
         */
        private $title;



        /**
         * Singleton of the class App
         * 
         * @return App
         */
        public static function getInstance() : App
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new App();
            }

            return self::$_instance;
        }



        /**
         * Get the title of the current page
         *
         * @return string|null
         */
        public function getTitle () : ?string
        {
            return $this->title;
        }



        /**
         * Set the title of the current page
         *
         * @return self
         */
        public function setTitle (string $title) : self
        {
            $this->title = " | $title";
            return $this;
        }
    }