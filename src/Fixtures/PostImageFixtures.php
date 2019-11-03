<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'post_image' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostImageFixtures extends AbstractFixtures
    {
        /**
         * @var PostFixtures
         */
        private $postFixtures;



        /**
         * @var ImageFixtures
         */
        private $imageFixtures;



        public function __construct (EntityManager $manager, object $entity, string $locale, PostFixtures $postFixtures, ImageFixtures $imageFixtures)
        {
            parent::__construct($manager, $entity, $locale);

            $this->postFixtures = $postFixtures;
            $this->imageFixtures = $imageFixtures;
        }



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $posts = $this->postFixtures->getPostsIds();
            $image = $this->imageFixtures->getImageId();

            foreach ($posts as $post) {
                $this->entity
                    ->setPost_id($post)
                    ->setImage_id($image);

                    $this->manager->insert($this->entity);
            }

            return $this;
        }
    }