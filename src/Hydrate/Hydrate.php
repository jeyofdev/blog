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
         * Add the associated categories to a post and 
         * get the value of a column of each associated category
         *
         * @param CategoryTable $tableCategory
         * @param Post $post
         * @param string $column A column of the table Category
         * @return void
         */
        public static function hydratePostBy (CategoryTable $tableCategory, Post $post, string $column) : void
        {
            self::hydratePost($tableCategory, $post);

            $method = "get" . ucfirst($column);

            $postCategories = [];
            foreach ($post->getCategories() as $category) {
                $postCategories[] = $category->$method();
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
                $post->setCategories();
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