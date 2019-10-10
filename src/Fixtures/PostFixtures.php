<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'post' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostFixtures extends AbstractFixtures
    {
        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $post = new Post($this->manager);

            for ($i = 0; $i < 20; $i++) { 
                $post
                    ->setName($this->faker->sentence(4, true))
                    ->setSlug($this->faker->slug)
                    ->setContent($this->faker->paragraphs(rand(5, 20), true))
                    ->setCreated_at($this->faker->dateTimeBetween('-3 years', 'now')->format("Y-m-d H:i:s"));

                $this->manager->insert($post);
            }

            return $this;
        }
    }