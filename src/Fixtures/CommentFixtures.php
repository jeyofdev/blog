<?php

    namespace jeyofdev\php\blog\Fixtures;


    /**
     * Add the fixtures in the 'comment' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentFixtures extends AbstractFixtures
    {
        /**
         * The ids of each comments
         *
         * @var array
         */
        private $commentsIds = [];



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            for ($i = 0; $i < 30; $i++) { 
                $this->entity
                    ->setContent($this->faker->sentence(20, true));

                $this->manager->insert($this->entity);
                $this->commentsIds[] = $this->manager->lastInsertId();
            }

            return $this;
        }



        /**
         * Get the value of commentsIds
         *
         * @return array
         */
        public function getCommentsIds () : array
        {
            return $this->commentsIds;
        }
    }