<?php

    namespace jeyofdev\php\blog\Controller\Security;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Entity\User;
    use jeyofdev\php\blog\Form\LoginForm;
    use jeyofdev\php\blog\Form\Validator\UserValidator;
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
            if (isset($_SESSION["auth"])) {
                $url = $_SESSION["URI"];
                Url::redirect(301, $url);
            }

            $tableUser = new UserTable($this->connection);

            $errors = []; // form errors

            // check that the form is valid and connect the user
            $validator = new UserValidator("en", $_POST);

            if ($validator->isSubmit()) {
                $user = new User();
                $user->setUsername($_POST["username"]);

                if ($validator->isValid()) {
                    $currentUser = $tableUser->find(["username" => $_POST["username"]]);

                    // check that the password is the same as the password of the database
                    if ($currentUser && (password_verify($_POST["password"], $currentUser->getPassword()))) {
                        $_SESSION["auth"] = $currentUser->getID();

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
            $flash = null;
            if (array_key_exists("form", $errors)) {
                $flash = '<div class="alert alert-danger my-5">The form contains errors</div>';
            } else if (array_key_exists("connection", $errors)) {
                $flash = '<div class="alert alert-danger my-5">Username or password is incorrect</div>';
            }

            if (isset($_GET["forbidden"])) {
                $flash = '<div class="alert alert-danger my-5">To access the pages of the admin, you must identify yourself</div>';
            }

            $title = App::getInstance()
                ->setTitle("Connection to the administration")
                ->getTitle();

            $this->render("security.auth.login", $this->router, compact("form", "url", "title", "flash"));
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