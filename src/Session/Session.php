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
         * @return self
         */
        public function setFlash (string $message, string $type = "success", ?string $class = null) : self
        {
            $class = !is_null($class) ? " $class" : null;

            $this->write("flash", [
                "message" => $message,
                "type" => $type,
                "class" => $class
            ]);

            return $this;
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
         * @return self
         */
        public function write (string $key, $value) : self
        {
            $_SESSION[$key] = $value;
            return $this;
        }



        /**
         * Empty the value of a session variable
         *
         * @return self
         */
        public function empty (string $key) : self
        {
            $_SESSION[$key] = [];
            return $this;
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