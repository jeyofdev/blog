<?php

    namespace jeyofdev\php\blog\Auth;


    use jeyofdev\php\blog\Session\Session;


    /**
     * Manage the session token
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Csrf
    {
        /**
         * @var Session
         */
        private $session;



        /**
         * The session token
         *
         * @var string
         */
        public $token;



        /**
         * @param Session $session
         */
        public function __construct(Session $session)
        {
            $this->session = $session;
            $this->token = $this->session->read("token");
        }


        /**
         * Check that the session variable "token" exists
         *
         * @return boolean
         */
        public function isValid() : bool
        {
            if (isset($_POST["token"]) && $_POST["token"] != $this->token) {
                return false;
            }

            return true;
        }



        /**
         * Set the session token
         *
         * @param integer $begin
         * @param integer $end
         * @param integer $multiplier
         * @return void
         */
        public function setSessionToken(int $begin, int $end, int $multiplier = 1): void
        {
            $number = time() * (rand($begin, $end) * $multiplier) + (rand(32, 276) * rand(5, 80));
            $this->token = password_hash($number, PASSWORD_DEFAULT);
            
            $this->session->write("token", $this->token);
        }
    }