<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'post_comment' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostCommentFixtures extends AbstractFixtures
    {
        /**
         * @var PostFixtures
         */
        private $postFixtures;



        /**
         * @var CommentFixtures
         */
        private $commentFixtures;



        public function __construct (EntityManager $manager, object $entity, string $locale, PostFixtures $postFixtures, CommentFixtures $commentFixtures)
        {
            parent::__construct($manager, $entity, $locale);

            $this->postFixtures = $postFixtures;
            $this->commentFixtures = $commentFixtures;
        }



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $posts = $this->postFixtures->getPostsIds();
            $comments = $this->commentFixtures->getCommentsIds();

            foreach ($posts as $post) {
                $randomComments = $this->faker->randomElements($comments, rand(0, count($comments)));
                
                foreach ($randomComments as $comment) {
                    $this->entity
                        ->setPost_id($post)
                        ->setComment_id($comment);

                    $this->manager->insert($this->entity);
                }
            }

            return $this;
        }
    }