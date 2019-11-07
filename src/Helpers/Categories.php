<?php

    namespace jeyofdev\php\blog\Helpers;


    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Router\Router;


    /**
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Categories
    {
        /**
         * Generate the categories of each posts in the views
         *
         * @param Router $router
         * @param Post $post
         * @return string
         */
        public static function getCategories (Router $router, Post $post) : string
        {
            $categories = array_map(function ($category) use ($router) {
                $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getslug()]); 
                return '<a href="' . $url . '">' . $category->getName() . '</a>';
            }, $post->getCategories());

            return implode(", ", $categories);
        }
    }