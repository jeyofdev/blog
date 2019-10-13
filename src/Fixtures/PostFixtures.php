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
         * {@inheritDoc}
         */
        public function add () : self
        {
            for ($i = 0; $i < 20; $i++) { 
                $this->entity
                    ->setName($this->faker->sentence(4, true))
                    ->setSlug($this->faker->slug)
                    ->setContent($this->faker->paragraphs(rand(5, 20), true))
                    ->setCreated_at($this->faker->dateTimeBetween('-3 years', 'now')->format("Y-m-d H:i:s"));

                $this->manager->insert($this->entity);
            }

            return $this;
        }
    }