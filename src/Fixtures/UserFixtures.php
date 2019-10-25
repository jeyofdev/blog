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
         * {@inheritDoc}
         */
        public function add () : self
        {
            $this->entity
                ->setUsername("admin")
                ->setPassword("admin");

            $this->manager->insert($this->entity);

            return $this;
        }
    }