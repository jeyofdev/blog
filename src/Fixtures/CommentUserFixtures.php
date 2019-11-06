<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'comment_user' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentUserFixtures extends AbstractFixtures
    {
        /**
         * @var CommentFixtures
         */
        private $commentFixtures;



        /**
         * @var UserFixtures
         */
        private $userFixtures;



        public function __construct (EntityManager $manager, object $entity, string $locale, CommentFixtures $commentFixtures, UserFixtures $userFixtures)
        {
            parent::__construct($manager, $entity, $locale);

            $this->userFixtures = $userFixtures;
            $this->commentFixtures = $commentFixtures;
        }



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $comments = $this->commentFixtures->getCommentsIds();
            $users = $this->userFixtures->getUsersIds();

            foreach ($comments as $comment) {
                $randomUsers = $this->faker->randomElements($users);

                foreach ($randomUsers as $user) {
                    $this->entity
                        ->setComment_id($comment)
                        ->setUser_id($user);

                    $this->manager->insert($this->entity);
                }
            }

            return $this;
        }
    }