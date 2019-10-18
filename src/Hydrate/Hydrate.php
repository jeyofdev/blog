<?php

    namespace jeyofdev\php\blog\Hydrate;


    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Table\CategoryTable;


    /**
     * Manage the hydration of posts
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Hydrate
    {
        /**
         * Add the associated categories to a post
         *
         * @param CategoryTable $tableCategory
         * @param Post $post
         * @return void
         */
        public static function hydratePost (CategoryTable $tableCategory, Post $post) : void
        {
            /**
             * @var Category[]
             */
            $categories = $tableCategory->findCategories(['id' => $post->getId()]);

            foreach ($categories as $category) {
                $post->addCategory($category);
            }
        }



        /**
         * Add the associated categories on each post
         *
         * @param CategoryTable $tableCategory
         * @return void
         */
        public static function hydrateAllPosts (CategoryTable $tableCategory, $posts) : void
        {
            // get the ids of each items
            $postsById = [];
            foreach ($posts as $post) {
                $postsById[$post->getId()] = $post;
            }
            $ids = implode(", ", array_keys($postsById));

            /**
             * @var Post[]
             */
            $categories = $tableCategory->findCategoriesByPosts($ids);

            foreach ($categories as $category) {
                $postsById[$category->getPost_Id()]->addCategory($category);
            }
        }
    }