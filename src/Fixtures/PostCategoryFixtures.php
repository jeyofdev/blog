<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'post_category' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostCategoryFixtures extends AbstractFixtures
    {
        /**
         * @var PostFixtures
         */
        private $postFixtures;



        /**
         * @var CategoryFixtures
         */
        private $categoryFixtures;



        public function __construct (EntityManager $manager, object $entity, string $locale, PostFixtures $postFixtures, CategoryFixtures $categoryFixtures)
        {
            parent::__construct($manager, $entity, $locale);

            $this->postFixtures = $postFixtures;
            $this->categoryFixtures = $categoryFixtures;
        }



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $posts = $this->postFixtures->getPostIds();
            $categories = $this->categoryFixtures->getCategoriesIds();

            foreach ($posts as $post) {
                $randomCategories = $this->faker->randomElements($categories, rand(0, count($categories)));

                foreach ($randomCategories as $category) {
                    $this->entity
                        ->setPost_id($post)
                        ->setCategory_id($category);

                    $this->manager->insert($this->entity);
                }
            }

            return $this;
        }
    }