<?php

    namespace jeyofdev\php\blog\Helpers;


    /**
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Text
    {
        /**
         * Generate an excerpt
         *
         * @return string
         */
        public static function excerpt (string $content, int $limit = 50) : string
        {
            if (mb_strlen($content) <= $limit) {
                return $content;
            }

            $lastSpace = mb_strpos($content, ' ', $limit);

            return mb_substr($content, 0, $lastSpace) . '...';
        }
    }