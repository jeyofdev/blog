<?php

    namespace jeyofdev\php\blog\Fixtures;


    /**
     * Add the fixtures in the 'user' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class UserFixtures extends AbstractFixtures
    {
        /**
         * The ids of each user
         *
         * @var array
         */
        private $usersIds = [];



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $this->entity
                ->setUsername("admin")
                ->setPassword("admin")
                ->setSlug("admin");
            $this->manager->insert($this->entity);
            $this->usersIds[] = $this->manager->lastInsertId();

            $this->entity
                ->setUsername("author")
                ->setPassword("author")
                ->setSlug("author");
            $this->manager->insert($this->entity);
            $this->usersIds[] = $this->manager->lastInsertId();

            return $this;
        }



        /**
         * Get the value of usersIds
         *
         * @return void
         */
        public function getUsersIds () : array
        {
            return $this->usersIds;
        }
    }