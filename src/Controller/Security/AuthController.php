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
         * Login to the admin
         *
         * @return void
         */
        public function login () : void
        {
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
                        $url = $this->router->url("admin");
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

            // // flash message
            $flash = null;
            if (array_key_exists("form", $errors)) {
                $flash = '<div class="alert alert-danger my-5">The form contains errors</div>';
            } else if (array_key_exists("connection", $errors)) {
                $flash = '<div class="alert alert-danger my-5">Username or password is incorrect</div>';
            }

            $title = App::getInstance()
                ->setTitle("Connection to the administration")
                ->getTitle();

            $this->render("security.auth.login", $this->router, compact("form", "url", "title", "flash"));
        }
    }