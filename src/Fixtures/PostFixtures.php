<?php

    namespace jeyofdev\php\blog\Fixtures;


    /**
     * Add the fixtures in the 'post' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostFixtures extends AbstractFixtures
    {
        /**
         * The ids of each post
         *
         * @var array
         */
        private $postsIds = [];



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            for ($i = 0; $i < 20; $i++) { 
                $this->entity
                    ->setName($this->faker->sentence(4, true))
                    ->setSlug($this->faker->slug)
                    ->setContent($this->faker->paragraphs(rand(5, 20), true))
                    ->setCreated_at($this->faker->dateTimeBetween('-3 years', 'now')->format("Y-m-d H:i:s"))
                    ->setPublished(rand(0, 1));

                $this->manager->insert($this->entity);
                $this->postsIds[] = $this->manager->lastInsertId();
            }

            return $this;
        }



        /**
         * Get the value of postsIds
         *
         * @return array
         */
        public function getPostsIds () : array 
        {
            return $this->postsIds;
        }
    }