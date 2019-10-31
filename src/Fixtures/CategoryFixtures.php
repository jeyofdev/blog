<?php

    namespace jeyofdev\php\blog\Fixtures;


    /**
     * Add the fixtures in the 'category' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CategoryFixtures extends AbstractFixtures
    {
        /**
         * The ids of each categories
         *
         * @var array
         */
        private $categoriesIds = [];



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            for ($i = 0; $i < 5; $i++) { 
                $this->entity
                    ->setName($this->faker->sentence(2))
                    ->setSlug($this->faker->slug);

                $this->manager->insert($this->entity);
                $this->categoriesIds[] = $this->manager->lastInsertId();
            }

            return $this;
        }



        /**
         * Get the value of categoriesIds
         *
         * @return array
         */
        public function getCategoriesIds () : array
        {
            return $this->categoriesIds;
        }
    }