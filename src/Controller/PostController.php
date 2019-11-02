<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Entity\Comment;
    use jeyofdev\php\blog\Pagination\Pagination;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Form\CommentForm;
    use jeyofdev\php\blog\Form\Validator\CommentValidator;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\CommentTable;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;
    use jeyofdev\php\blog\Url;


    /**
     * Manage the controller of the posts
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostController extends AbstractController
    {
        /**
         * Set the datas of the page which lists the posts of the blog
         *
         * @return void
         */
        public function index () : void
        {
            $tablePost = new PostTable($this->connection);
            $tableUser = new UserTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);

            /**
             * @var Post[]
             */
            $posts = $tablePost->findAllPostsPaginated(6, "created_at", "desc", ["published" => 1]);

            if (!empty($posts)) {
                // hydrate the posts
                PostHydrate::addCategoriesToAllPosts($tableCategory, $posts);
                PostHydrate::addUserToAllPosts($tableUser, $posts);
            }

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // Get the route of the current page
            $link = $this->router->url("blog");

            App::getInstance()->setTitle("List of posts");

            $this->render("post.index", $this->router, $this->session, compact("posts", "pagination", "link"));
        }



        /**
         * Set the datas of the page that displays a post
         *
         * @return void
         */
        public function show () : void
        {
            $errors = []; // form errors
            $flash = null; // flash message
            $buttonLabel = "Add a comment";

            $tablePost = new PostTable($this->connection);
            $tableUser = new UserTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);
            $tableRole = new RoleTable($this->connection);
            $tableComment = new CommentTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            /**
             * @var Post|false
             */
            $post = $tablePost->find(["id" => $id]);

            // check the post
            $this->exists($post, "post", $id);
            $this->checkSlugMatch($this->router, $post, $slug, $id);

            // hydrate the post
            PostHydrate::addCategoriesToPost($tableCategory, $post);
            PostHydrate::addUserToPost($tableUser, $tableRole, $post);

            $this->session->write("post", [
                "id" => $post->getId(),
                "slug" => $post->getSlug()
            ]);

            // related posts
            $relatedPosts = $tablePost->findRandomPosts(3);

            // comments of the post
            $comments = $tableComment->findAll();
            $countComments = count($tableComment->findCommentsByPost(["id" => $id]));

            /**
             * @var Comment[]
             */
            $postComments = $tableComment->findCommentsPaginated($post->getId(), 50);     

            /**
             * @var Pagination
             */
            $pagination = $tableComment->getPagination();

            // check that the form is valid and add or update a comment
            $validator = new CommentValidator("en", $_POST);

            if ($validator->isSubmit()) {
                $comment = new Comment();

                if (isset($_POST["content"])) {
                    $comment
                        ->setId($_POST["id"])
                        ->setUsername($_POST["username"])
                        ->setContent($_POST["content"]);

                    if ($validator->isValid()) {
                        if ($_POST["id"] === "") {
                            $tableComment->createComment($comment, $post);
                            $this->session->setFlash("Your comment has been added", "success", "mt-5"); // flash message
                        } else {
                            $tableComment->updateComment($comment, $post);
                            $this->session->setFlash("Your comment has been updated", "success", "mt-5"); // flash message
                        }

                        $url = $this->router->url("post", ["id" => $id, "slug" => $slug]) . "?create=1";
                        Url::redirect(301, $url);
                    } else {
                        $errors = $validator->getErrors();
                    }
                } else if (isset($_POST["id"])) {
                    $comment->setId($_POST["id"]);
                    $comment = $tableComment->find(["id" => $_POST["id"]]);
                    $_POST = [
                        "id" => $comment->getId(),
                        "username" => $comment->getUsername(),
                        "content" => $comment->getContent()
                    ];
                    $buttonLabel = "Update a comment";
                }
            }

            // form
            $form = new CommentForm($_POST, $errors);

            // url of the current page
            $url = $this->router->url("post", ["slug" => $slug, "id" => $id]);

            // flash message
            if (!empty($errors)) {
                $this->session->setFlash("The form contains errors", "danger", "mt-5");
            }
            $flash = $this->session->generateFlash();

            // Get the route of the current page
            $link = $this->router->url("post", ["id" => $id, "slug" => $slug]);    

            $title = App::getInstance()->setTitle($post->getName())->getTitle();

            $this->render("post.show", $this->router, $this->session, compact("post", "relatedPosts", "postComments", "countComments", "pagination", "form", "buttonLabel", "url", "link", "title", "flash"));
        }
    }