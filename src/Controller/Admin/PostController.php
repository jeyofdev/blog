<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Form\PostForm;
    use jeyofdev\php\blog\Form\Validator\PostValidator;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Url;
    use PDO;


    /**
     * Manage the controller of the posts in the admin
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostController extends AbstractController
    {
        /**
         * List the posts
         *
         * @return void
         */
        public function index () : void
        {
            $tablePost = new PostTable($this->connection);

            /**
             * @var Post[]
             */
            $posts = $tablePost->findAllPostsPaginated(10, "id", "desc");

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // get the route of the current page
            $link = $this->router->url("admin_posts");

            // flash message
            $flash = null;
            if (isset($_GET["delete"])) {
                $flash = '<div class="alert alert-success my-5">The post has been deleted</div>';
            } else if (isset($_GET["create"])) {
                $flash = '<div class="alert alert-success my-5">The post has been created</div>';
            }

            $title = App::getInstance()
                ->setTitle("Administration of posts")
                ->getTitle();

            $this->render("admin.post.index", $this->router, compact("posts", "pagination", "link", "title", "flash"));
        }



        /**
         * Delete a post
         *
         * @return void
         */
        public function delete () : void
        {
            $tablePost = new PostTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            $tablePost->delete(["id" => $id]);

            // redirect to the home of the admin
            $url = $this->router->url("admin_posts") . "?delete=1";
            Url::redirect(301, $url);
        }



        /**
         * Edit a post
         *
         * @return void
         */
        public function edit () : void
        {
            $tablePost = new PostTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            /**
             * @var Post|null
             */
            $post = $tablePost->find(["id" => $id]);

            $success = false; // query success
            $errors = []; // form errors

            // check that the form is valid and update the post
            $validator = new PostValidator("en", $_POST, $tablePost, $post->getId());

            if ($validator->isSubmit()) {
                $post
                    ->setName($_POST["name"])
                    ->setSlug($_POST["slug"])
                    ->setContent($_POST["content"]);

                if ($validator->isValid()) {
                    $tablePost->updatePost($post, "Europe/Paris");
                    $success = true;
                } else {
                    $errors = $validator->getErrors();
                }
            }

            // form
            $form = new PostForm($post, $errors);

            // flash message
            $flash = null;
            if ($success) {
                $flash = '<div class="alert alert-success my-5">The post has been updated</div>';
            }

            if (!empty($errors)) {
                $flash = '<div class="alert alert-danger my-5">The post could not be updated</div>';
            }

            $title = App::getInstance()
                ->setTitle("Edit the post with the id : $id")
                ->getTitle();

            $this->render("admin.post.edit", $this->router, compact("post", "form", "title", "flash"));
        }



        /**
         * Add a new post
         *
         * @return void
         */
        public function new () : void
        {
            $tablePost = new PostTable($this->connection);

            $errors = []; // form errors

            // check that the form is valid and update the post
            $validator = new PostValidator("en", $_POST, $tablePost);

            if ($validator->isSubmit()) {
                $post = new Post();
                $post
                    ->setName($_POST["name"])
                    ->setSlug($_POST["slug"])
                    ->setContent($_POST["content"]);

                if ($validator->isValid()) {
                    $tablePost->createPost($post, "Europe/Paris");

                    $url = $this->router->url("admin_posts", ["id" => $post->getId()]) . "?create=1";
                    Url::redirect(301, $url);
                } else {
                    $errors = $validator->getErrors();
                }
            }

            // form
            $form = new PostForm($_POST, $errors);

            // flash message
            $flash = null;
            if (!empty($errors)) {
                $flash = '<div class="alert alert-danger my-5">The post could not be created</div>';
            }

            $title = App::getInstance()
                ->setTitle("Add a new post")
                ->getTitle();

            $this->render("admin.post.new", $this->router, compact("form", "title", "flash"));
        }
    }