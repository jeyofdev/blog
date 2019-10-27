<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Core\Auth;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Form\PostForm;
    use jeyofdev\php\blog\Form\Validator\PostValidator;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Url;


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
            // check that the user is logged in
            Auth::isConnect($this->router);

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
            $flash = $this->session->generateFlash();

            $title = App::getInstance()
                ->setTitle("Administration of posts")
                ->getTitle();

            $this->render("admin.post.index", $this->router, $this->session, compact("posts", "pagination", "link", "title", "flash"));
        }



        /**
         * Delete a post
         *
         * @return void
         */
        public function delete () : void
        {
            // check that the user is logged in
            Auth::isConnect($this->router);

            $tablePost = new PostTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            $tablePost->delete(["id" => $id]);

            // flash message
            $this->session->setFlash("The post has been deleted", "success", "my-5");

            // redirect to the home of the admin
            $url = $this->router->url("admin_posts") . "?delete=1";
            Url::redirect(301, $url);
        }



        /**
         * Update a post
         *
         * @return void
         */
        public function edit () : void
        {
            $success = false; // query success
            $errors = []; // form errors
            $flash = null; // flash message

            // check that the user is logged in
            Auth::isConnect($this->router);

            $tablePost = new PostTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            /**
             * @var Post|null
             */
            $post = $tablePost->find(["id" => $id]);

            // the names of all categories
            $categories = $tableCategory->list("name");

            // the categories associated with the current post
            PostHydrate::hydratePostBy($tableCategory, $post, "name");

            // check that the form is valid and update the post
            $validator = new PostValidator("en", $_POST, $tablePost, $categories, $post->getId());

            if ($validator->isSubmit()) {
                $post
                    ->setName($_POST["name"])
                    ->setSlug($_POST["slug"])
                    ->setContent($_POST["content"]);

                if ($validator->isValid()) {
                    $tablePost->updatePost($post, "Europe/Paris", "post_category");
                    PostHydrate::hydrateAllPosts($tableCategory, [$post]);
                    $success = true;
                } else {
                    $errors = $validator->getErrors();
                }
            }

            // form
            $form = new PostForm($post, $errors);

            // url of the current page
            $url = $this->router->url("admin_post", ["id" => $id]);

            // flash messages
            if ($success) {
                $this->session->setFlash("The post has been updated", "success", "my-5");
                $flash = $this->session->generateFlash();
            }

            if (!empty($errors)) {
                $this->session->setFlash("The post could not be updated", "danger", "my-5");
                $flash = $this->session->generateFlash();
            }

            $title = App::getInstance()
                ->setTitle("Edit the post with the id : $id")
                ->getTitle();

            $this->render("admin.post.edit", $this->router, $this->session, compact("categories", "form", "url", "title", "flash"));
        }



        /**
         * Add a new post
         *
         * @return void
         */
        public function new () : void
        {
            $errors = []; // form errors
            $flash = null; // flash message

            // check that the user is logged in
            Auth::isConnect($this->router);

            $tablePost = new PostTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);

            // the names of all categories
            $categories = $tableCategory->list("name");

            // check that the form is valid and create the post
            $validator = new PostValidator("en", $_POST, $tablePost, $categories);

            if ($validator->isSubmit()) {
                $post = new Post();
                $post
                    ->setName($_POST["name"])
                    ->setSlug($_POST["slug"])
                    ->setContent($_POST["content"]);

                if ($validator->isValid()) {
                    $tablePost->createPost($post, "Europe/Paris");
                    PostHydrate::hydrateAllPosts($tableCategory, [$post]);
                    
                    $this->session->setFlash("The post has been created", "success", "my-5"); // flash message

                    $url = $this->router->url("admin_posts", ["id" => $post->getId()]) . "?create=1";
                    Url::redirect(301, $url);
                } else {
                    $errors = $validator->getErrors();
                }
            }

            // form
            $form = new PostForm($_POST, $errors);

            // url of the current page
            $url = $this->router->url("admin_post_new");

            // flash message
            if (!empty($errors)) {
                $this->session->setFlash("The post could not be created", "danger", "my-5");
                $flash = $this->session->generateFlash();
            }

            $title = App::getInstance()
                ->setTitle("Add a new post")
                ->getTitle();

            $this->render("admin.post.new", $this->router, $this->session, compact("categories", "form", "url", "title", "flash"));
        }
    }