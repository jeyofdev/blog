<?php

    namespace jeyofdev\php\blog\Hydrate;


    use jeyofdev\php\blog\Session\Session;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;


    /**
     * Manage the hydration of comments
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CommentHydrate
    {
        /**
         * Add the associated user to a comment
         *
         * @param UserTable $tableUser
         * @param RoleTable $tableRole
         * @return void
         */
        public static function addUserToComment (UserTable $tableUser, RoleTable $tableRole, $comments) : void
        {
            foreach ($comments as $comment) {
                /**
                 * @var User
                 */
                $user = $tableUser->findUserbyComment(["id" => $comment->getId()]);
                UserHydrate::AddRole($tableRole, $user);
                $comment->addUser($user);
            }
        }



        /**
         * Add the associated user when add a comment
         *
         * @param UserTable $tableUser
         * @param RoleTable $tableRole
         * @param Session $session
         * @return void
         */
        public static function addUserWhenAddComment (UserTable $tableUser, RoleTable $tableRole, $comment, Session $session) : void
        {
            /**
             * @var User
             */
            $user = $tableUser->findUserbyComment(["id" => $session->read("auth")]);
            UserHydrate::AddRole($tableRole, $user);
            $comment->addUser($user);
        }
    }