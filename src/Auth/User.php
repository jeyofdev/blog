<?php

    namespace jeyofdev\php\blog\Auth;


    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Exception\UnauthorizedException;
    use jeyofdev\php\blog\Hydrate\UserHydrate;
    use jeyofdev\php\blog\Session\Session;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;


    class User
    {
        /**
         * @var Session
         */
        private $session;



        /**
         * @var UserTable
         */
        private $tableUser;



        /**
         * @var RoleTable
         */
        private $tableRole;



        /**
         * @param Session $session
         * @param UserTable $tableUser
         * @param RoleTable $tableRole
         */
        public function __construct (Session $session, UserTable $tableUser, RoleTable $tableRole)
        {
            $this->session = $session;
            $this->tableUser = $tableUser;
            $this->tableRole = $tableRole;
        }



        /**
         * Check that the user is authorized to access a page
         *
         * @param Post $post
         * @return void
         */
        public function isAuthorized(Post $post)
        {
            if ($this->session->read("role") !== "admin") {
                $user = $this->tableUser->find(["id" => $this->session->read("auth")]);
                UserHydrate::AddRole($this->tableRole, $user);

                if ($this->session->read("role") !== $post->getUser()->getRole()) {
                    throw new UnauthorizedException("You are not authorized to access this page");
                }
            }
        }
    }