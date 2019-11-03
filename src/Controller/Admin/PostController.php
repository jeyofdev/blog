<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Auth\Auth;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Exception\UnauthorizedException;
    use jeyofdev\php\blog\Form\PostForm;
    use jeyofdev\php\blog\Form\Validator\PostValidator;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Hydrate\UserHydrate;
    use jeyofdev\php\blog\Image\Image;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\ImageTable;
    use jeyofdev\php\blog\Table\PostImageTable;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;
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
            $tableUser = new UserTable($this->connection);

            /**
             * @var Post[]
             */
            if (Auth::isAdmin($this->session)) {
                $posts = $tablePost->findAllPostsPaginated(5, "id", "desc");
            } else {
                $user = $tableUser->find(["id" => $this->session->read("auth")]);
                $posts = $tablePost->findPostsPaginatedByUser($user, 5, "id", "desc");
            }

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
            $tableImage = new ImageTable($this->connection);
            $tablePostImage = new PostImageTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            /**
             * @var Post
             */
            $post = $tablePost->find(["id" => $id]);

            Image::deleteImage($post, $tablePostImage, $tableImage);
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
            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);

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
            PostHydrate::addCategoriesToPostBy($tableCategory, $post, "name");
            PostHydrate::addUserToPost($tableUser, $tableRole, $post);

            if ($this->session->read("role") !== "admin") {
                $currentUser = $tableUser->find(["id" => $this->session->read("auth")]);
                UserHydrate::AddRole($tableRole, $currentUser);

                if ($this->session->read("role") !== $post->getUser()->getRole()) {
                    throw new UnauthorizedException("You are not authorized to access this page");
                }
            }

            // check that the form is valid and update the post
            $validator = new PostValidator("en", $_POST, $tablePost, $categories, $post->getId());

            if ($validator->isSubmit()) {
                $post
                    ->setName($_POST["name"])
                    ->setSlug(str_replace(" ", "-", $_POST["slug"]))
                    ->setContent($_POST["content"]);

                if ($validator->isValid()) {
                    $tablePost->updatePost($post, "Europe/Paris", "post_category");
                    $tablePost->attachCategories($post->getID(), ["post_id" => $post->getID(), "category_id" => $_POST["categoriesIds"]]);
                    PostHydrate::addCategoriesToAllPosts($tableCategory, [$post]);
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
            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);
            $tableImage = new ImageTable($this->connection);

            // the names of all categories
            $categories = $tableCategory->list("name");

            $image = new Image();

            // check that the form is valid and create the post
            $validator = new PostValidator("en", $_POST, $tablePost, $categories);

            if ($validator->isSubmit()) {
                $post = new Post();
                $post
                    ->setName($_POST["name"])
                    ->setSlug(str_replace(" ", "-", $_POST["slug"]))
                    ->setContent($_POST["content"]);

                if ($validator->isValid()) {
                    $image->addImage($post, $tableImage);
                    $entityImage = $image->getImage();

                    if (!array_key_exists("extension", $image->getError())) {
                        $tablePost->createPost($post, $entityImage, "Europe/Paris");
                        $tableUser->addToPost($post->getId(), $this->session->read("auth"));
    
                        PostHydrate::addCategoriesToAllPosts($tableCategory, [$post]);
                        PostHydrate::addUserToPost($tableUser, $tableRole, $post);
                        
                        $this->session->setFlash("The post has been created", "success", "my-5"); // flash message
    
                        $url = $this->router->url("admin_posts", ["id" => $post->getId()]) . "?create=1";
                        Url::redirect(301, $url);
                    }
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
            } if (!empty($image->getError())) {
                $this->session->setFlash("The uploaded image to an extension that is not valid. The authorized extensions are : {$image->getAllowedExtensions()}", "danger", "my-5");
                $flash = $this->session->generateFlash();
            }

            $title = App::getInstance()
                ->setTitle("Add a new post")
                ->getTitle();

            $this->render("admin.post.new", $this->router, $this->session, compact("categories", "form", "url", "title", "flash"));
        }



        /**
         * Publish a post
         *
         * @return void
         */
        public function publish () : void
        {
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

            $post->setPublished(1);
            PostHydrate::addCategoriesToAllPosts($tableCategory, [$post]);

            // get the ids of the categories to the post
            $categoriesIds = [];
            foreach ($post->getCategories() as $category) {
                $categoriesIds[] = $category->getId();
            }

            // publish the post
            $tablePost->publishPost($post, "Europe/Paris");
            $tablePost->attachCategories($post->getID(), ["post_id" => $post->getID(), "category_id" => $categoriesIds]);

            // flash message
            $this->session->setFlash("The post has been published", "success", "my-5");

            // redirect to the home of the admin
            $url = $this->router->url("admin_posts") . "?publish=1";
            Url::redirect(301, $url);
        }
    }