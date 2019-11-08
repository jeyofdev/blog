<?php

    namespace jeyofdev\php\blog\Controller\Security;


    use jeyofdev\php\blog\App;
use jeyofdev\php\blog\Auth\Csrf;
use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Entity\User;
    use jeyofdev\php\blog\Form\LoginForm;
    use jeyofdev\php\blog\Form\RegisterForm;
    use jeyofdev\php\blog\Form\Validator\RegisterValidator;
    use jeyofdev\php\blog\Form\Validator\UserValidator;
    use jeyofdev\php\blog\Hydrate\UserHydrate;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;
    use jeyofdev\php\blog\Url;


    /**
     * Manage the controller of the connexion to admin
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class AuthController extends AbstractController
    {
        /**
         * Log in
         *
         * @return void
         */
        public function login () : void
        {
            $this->getHttpReferer();

            // if the user is already logged in,
            // redirection to the previous page
            if ($this->session->exist("auth")) {
                $url = $this->session->read("URI");
                Url::redirect(301, $url);
            }

            $errors = []; // form errors
            $flash = null; // flash message

            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);

            // check that the form is valid and connect the user
            $validator = new UserValidator("en", $_POST);

            if ($validator->isSubmit()) {
                $user = new User();
                $user->setUsername($_POST["username"]);

                if ($validator->isValid()) {
                    $currentUser = $tableUser->find(["username" => $_POST["username"]]);

                    // check that the password is the same as the password of the database
                    if ($currentUser && (password_verify($_POST["password"], $currentUser->getPassword()))) {
                        UserHydrate::AddRole($tableRole, $currentUser);
                        $this->session
                            ->write("auth", $currentUser->getId())
                            ->write("role", $currentUser->getRole());

                        // set the token csrf
                        if (!$this->session->exist("token")) {
                            $csrf = new Csrf($this->session);
                            $csrf->setSessionToken(175, 658, 5);
                        }

                        // redirection to the previous page
                        $url = $this->getHttpRefererRouteName();

                        if ($url === "/login/?forbidden=1") {
                            $url = $this->router->url("admin");
                        }

                        Url::redirect(301, $url);
                    } else {
                        $errors["connection"] = true;
                    }
                } else {
                    $errors = $validator->getErrors();
                    $errors["form"] = true;
                }
            }

            // form
            $form = new LoginForm($_POST, $errors);

            // url of the current page
            $url = $this->router->url("login");

            // flash message
            if (array_key_exists("form", $errors)) {
                $this->session->setFlash("The form contains errors", "danger", "mt-5");
            } else if (array_key_exists("connection", $errors)) {
                $this->session->setFlash("Username or password is incorrect", "danger", "mt-5");
            } else if (isset($_GET["forbidden"])) {
                $this->session->setFlash("To access the pages of the admin, you must identify yourself", "danger", "mt-5");
            }

            $flash = $this->session->generateFlash();

            $title = App::getInstance()
                ->setTitle("Connection to the administration")
                ->getTitle();

            $this->render("security.auth.login", $this->router, $this->session, compact("form", "url", "title", "flash"));
        }



        /**
         * Log out
         *
         * @return void
         */
        public function logout () : void
        {
            session_destroy();

            $url = $this->getHttpRefererRouteName();

            if ($url === "/admin/") {
                $url = $this->router->url("home");
            }

            Url::redirect(301, $url);
        }



        /**
         * Register a new user
         *
         * @return void
         */
        public function register () : void
        {
            $this->getHttpReferer();

            // if the user is already logged in,
            // redirection to the previous page
            if ($this->session->exist("auth")) {
                $url = $this->router->url("admin");
                Url::redirect(301, $url);
            }

            $errors = []; // form errors
            $flash = null; // flash message

            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);

            // form validator
            $validator = new RegisterValidator("en", $_POST, $tableUser);

            // check that the form is valid and add the new user
            if ($validator->isSubmit()) {
                if ($validator->isValid()) {
                    $role = $tableRole->find(["name" => "author"]);

                    $user = new User();
                    $user
                        ->setUsername($_POST["username"])
                        ->setPassword($_POST["password"])
                        ->setSlug(str_replace(" ", "-", $_POST["username"]))
                        ->setRole($role);

                    $tableUser->createUser($user, $role);

                    $this->session->setFlash("Congratulations {$user->getUsername()}, you are now registered", "success", "mt-5");
                    $this->session
                        ->write("auth", $user->getId())
                        ->write("role", $user->getRole());

                    // set the token csrf
                    if (!$this->session->exist("token")) {
                        $csrf = new Csrf($this->session);
                        $csrf->setSessionToken(175, 658, 5);
                    }

                    $url = $this->router->url("admin") . "?user=1&create=1";
                    Url::redirect(301, $url);
                } else {
                    $errors = $validator->getErrors();
                    $errors["form"] = true;
                }
            }

            // form
            $form = new RegisterForm($_POST, $errors);

            // url of the current page
            $url = $this->router->url("register");

            // flash message
            if (array_key_exists("form", $errors)) {
                $this->session->setFlash("The form contains errors", "danger", "mt-5");
                $flash = $this->session->generateFlash();
            }

            $title = App::getInstance()
                ->setTitle("Register")
                ->getTitle();

            $this->render("security.auth.register", $this->router, $this->session, compact("form", "url", "title", "flash"));
        }



        /**
         * Save the url of the redirected page
         *
         * @return void
         */
        private function getHttpReferer () : void
        {
            $url = $this->getHttpRefererRouteName();

            if ($url !== "/login/") {
                $_SESSION["HTTP_REFERER"] = $url;
            } else if ($url === "/login/") {
                $urlLength = strlen($url);
                $_SERVER["HTTP_REFERER"] = substr($_SERVER["HTTP_REFERER"], 0, -$urlLength) . $_SESSION["HTTP_REFERER"];
            }
        }



        /**
         * Get the name of the route corresponding to the redirected page
         *
         * @return string
         */
        private function getHttpRefererRouteName () : string
        {
            if (isset($_SERVER["HTTP_REFERER"])) {
                $posFirstSlash = strpos($_SERVER["HTTP_REFERER"], "/", 8);
                $url = substr($_SERVER["HTTP_REFERER"], $posFirstSlash);
            } else {
                $url = "/admin/";
            }

            return $url;
        }
    }