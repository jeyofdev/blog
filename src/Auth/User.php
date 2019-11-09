<?php

    namespace jeyofdev\php\blog\Auth;


    use jeyofdev\php\blog\Entity\Comment;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Hydrate\UserHydrate;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Session\Session;
    use jeyofdev\php\blog\Table\CommentUserTable;
    use jeyofdev\php\blog\Table\PostUserTable;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;
    use jeyofdev\php\blog\Url;


    class User
    {
        /**
         * @var Router
         */
        private $router;



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
        public function __construct (Router $router, Session $session, UserTable $tableUser, RoleTable $tableRole)
        {
            $this->router = $router;
            $this->session = $session;
            $this->tableUser = $tableUser;
            $this->tableRole = $tableRole;
        }



        /**
         * Check that the user is authorized to access a page
         *
         * @param Post $post
         * @param Router $router
         * @return void
         */
        public function isAuthorized(Post $post, string $route)
        {
            if ($this->session->read("role") !== "admin") {
                $user = $this->tableUser->find(["id" => $this->session->read("auth")]);
                UserHydrate::AddRole($this->tableRole, $user);

                if ($this->session->read("role") !== $post->getUser()->getRole()) {
                    $this->session->setFlash("You are not authorized to access this page", "danger", "mt-5");

                    $url = $this->router->url($route) . "?delete=1";
                    Url::redirect(301, $url);
                }
            }
        }



        /**
         * Check that an action (delete, publish ...) is allowed on the posts
         *
         * @param Post $post
         * @param PostUserTable $tablePostUser
         * @return void
         */
        public function actionIsAuthorized(Post $post, PostUserTable $tablePostUser, string $route, string $message, string $action) : void
        {
            $postUser = $tablePostUser->find(["post_id" => $post->getId()])->getUser_id();

            if ($this->session->read("role") !== "admin" && $postUser !== $this->session->read("auth")) {
                $this->session->setFlash($message, "danger", "mt-5");

                $url = $this->router->url($route) . "?$action=1";
                Url::redirect(301, $url);
            }
        }



        /**
         * Check that an action is allowed on the comments
         *
         * @param Comment $comment
         * @param CommentUserTable $tableCommentUser
         * @param string $route
         * @param string $message
         * @param string $action
         * @return void
         */
        public function actionIsAuthorizedForComment(Comment $comment, CommentUserTable $tableCommentUser, string $route, string $message, string $action) : void
        {
            $commentUser = $tableCommentUser->find(["comment_id" => $comment->getId()])->getUser_id();

            if ($this->session->read("role") !== "admin" && $commentUser !== $this->session->read("auth")) {
                $this->session->setFlash($message, "danger", "mt-5");

                $pos = strrpos($_SERVER["HTTP_REFERER"], "/");
                $uri = substr($_SERVER["HTTP_REFERER"], 0, $pos + 1);
                $url = $uri . "?comment=1&$action=1";
                Url::redirect(301, $url);
            }
        }
    }