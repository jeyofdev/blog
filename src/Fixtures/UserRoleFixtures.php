<?php

    namespace jeyofdev\php\blog\Fixtures;


    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Add the fixtures in the 'user_role' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class UserRoleFixtures extends AbstractFixtures
    {
        /**
         * @var UserFixtures
         */
        private $userFixtures;



        /**
         * @var RoleFixtures
         */
        private $roleFixtures;



        public function __construct (EntityManager $manager, object $entity, string $locale, UserFixtures $userFixtures, RoleFixtures $roleFixtures)
        {
            parent::__construct($manager, $entity, $locale);

            $this->roleFixtures = $roleFixtures;
            $this->userFixtures = $userFixtures;
        }



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $users = $this->userFixtures->getUsersIds();
            $roles = $this->roleFixtures->getRolesIds();

            foreach ($users as $k => $user) {
                $this->entity
                    ->setUser_id($user)
                    ->setRole_id($roles[$k]);

                $this->manager->insert($this->entity);
            }

            return $this;
        }
    }