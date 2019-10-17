<?php

    namespace jeyofdev\php\blog;


    use jeyofdev\php\blog\Helpers\Helpers;


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
            return (!is_null($this->title)) ? Helpers::e($this->title) : "Home";
        }



        /**
         * Set the title of the current page
         *
         * @return self
         */
        public function setTitle (string $title, ?string $prefix = null) : self
        {
            $this->title = (!is_null($prefix)) ? "$prefix $title" : $title;
            return $this;
        }
    }