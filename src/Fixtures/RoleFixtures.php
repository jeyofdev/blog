<?php

    namespace jeyofdev\php\blog\Fixtures;


    /**
     * Add the fixtures in the 'role' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class RoleFixtures extends AbstractFixtures
    {
        /**
         * The ids of each role
         *
         * @var array
         */
        private $rolesIds = [];



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $roles = ["admin", "author"];

            foreach ($roles as $role) {
                $this->entity->setName($role);
                $this->manager->insert($this->entity);
                $this->rolesIds[] = $this->manager->lastInsertId();
            }

            return $this;
        }



        /**
         * Get the value of rolesIds
         *
         * @return void
         */
        public function getRolesIds () : array
        {
            return $this->rolesIds;
        }
    }