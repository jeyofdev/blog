<?php

    namespace jeyofdev\php\blog\Helpers;


    /**
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Helpers
    {
        /**
         * Convert all applicable characters to HTML entities
         *
         * @return string
         */
        public static function e (string $content) : string
        {
            return htmlentities($content);
        }
    }
