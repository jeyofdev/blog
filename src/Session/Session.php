<?php

    namespace jeyofdev\php\blog\Session;


    /**
     * Manage the session and the flash messages
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Session
    {
        public function __construct ()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }



        /**
         * Generate the flash message
         *
         * @return string|null
         */
        public function generateFlash(string $surround = "div") : ?string
        {
            if ($this->read("flash")) {
                extract($this->read("flash"));
                $html = '';
    
                if(isset($message)){
                    $html = '<' . $surround . ' class="alert alert-' . $type . $class . '">' . $message . '</' . $surround . '>';
                    $this->empty("flash");
                }
    
                return $html;
            }

            return null;
        }



        /**
         * Set the flash message
         *
         * @return void
         */
        public function setFlash (string $message, string $type = "success", ?string $class = null) : void
        {
            $class = !is_null($class) ? " $class" : null;

            $this->write("flash", [
                "message" => $message,
                "type" => $type,
                "class" => $class
            ]);
        }



        /**
         * get the value of a session variable
         *
         * @return mixed
         */
        public function read (?string $key = null)
        {
            if($key){
                if(isset($_SESSION[$key])){
                    return $_SESSION[$key];
                }
                
                return false;
            }

            return $_SESSION;
        }



        /**
         * Set the value of a session variable
         *
         * @return void
         */
        public function write (string $key, $value) : void
        {
            $_SESSION[$key] = $value;
        }



        /**
         * Empty the value of a session variable
         *
         * @return void
         */
        public function empty (string $key) : void
        {
            $_SESSION[$key] = [];
        }



        /**
         * Check that a session variable exists
         *
         * @return bool
         */
        public function exist (string $key) : bool
        {
            if (isset($_SESSION[$key])) {
                return true;
            }

            return false;
            
        }
    }