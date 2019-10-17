<?php

    namespace jeyofdev\php\blog\Hydrate;


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
         * Add the associated categories on posts
         *
         * @param CategoryTable $tableCategory
         * @return void
         */
        public static function hydratePosts (CategoryTable $tableCategory, $posts) : void
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