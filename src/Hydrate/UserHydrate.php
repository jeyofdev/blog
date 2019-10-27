<?php

    namespace jeyofdev\php\blog\Hydrate;


    use jeyofdev\php\blog\Entity\User;
    use jeyofdev\php\blog\Table\RoleTable;


    /**
     * Manage the hydration of the users
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class UserHydrate
    {
        /**
         * Add the role to an user
         *
         * @param RoleTable $tableRole
         * @param User $user
         * @return void
         */
        public static function AddRole (RoleTable $tableRole, User $user) : void
        {
            /**
             * @var Role
             */
            $role = $tableRole->findRole(["id" => $user->getId()]);
            $user->setRole($role);
        }
    }