<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'post_user' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostUserFixtures extends AbstractFixtures
    {
        /**
         * @var PostFixtures
         */
        private $postFixtures;



        /**
         * @var UserFixtures
         */
        private $userFixtures;



        public function __construct (EntityManager $manager, object $entity, string $locale, PostFixtures $postFixtures, UserFixtures $userFixtures)
        {
            parent::__construct($manager, $entity, $locale);

            $this->postFixtures = $postFixtures;
            $this->userFixtures = $userFixtures;
        }



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $posts = $this->postFixtures->getPostsIds();
            $users = $this->userFixtures->getUsersIds();

            foreach ($posts as $post) {
                $randomUsers = $this->faker->randomElements($users);

                foreach ($randomUsers as $user) {
                    $this->entity
                        ->setPost_id($post)
                        ->setUser_id($user);

                    $this->manager->insert($this->entity);
                }
            }

            return $this;
        }
    }