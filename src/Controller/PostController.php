<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Entity\Comment;
    use jeyofdev\php\blog\Pagination\Pagination;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Form\CommentForm;
    use jeyofdev\php\blog\Form\Validator\CommentValidator;
    use jeyofdev\php\blog\Hydrate\CommentHydrate;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\CommentTable;
use jeyofdev\php\blog\Table\CommentUserTable;
use jeyofdev\php\blog\Table\ImageTable;
    use jeyofdev\php\blog\Table\PostImageTable;
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
            $tableRole = new RoleTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);
            $tableImage = new ImageTable($this->connection);
            $tablePostImage = new PostImageTable($this->connection);

            /**
             * @var Post[]
             */
            $posts = $tablePost->findAllPostsPaginated(7, "created_at", "desc", ["published" => 1]);
            $firstPost = array_shift($posts);

            PostHydrate::hydrateAllPostsPaginated($posts, $firstPost, $tableCategory, $tableUser, $tableRole, $tableImage, $tablePostImage);

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // Get the route of the current page
            $link = $this->router->url("blog");

            App::getInstance()->setTitle("List of posts");

            $this->render("post.index", $this->router, $this->session, compact("posts", "firstPost", "pagination", "link"));
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
            $tableImage = new ImageTable($this->connection);
            $tablePostImage = new PostImageTable($this->connection);
            $tableCommentUser = new CommentUserTable($this->connection);

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
            PostHydrate::hydratePost($post, $tableCategory, $tableUser, $tableRole, $tableImage, $tablePostImage);

            $this->session->write("post", [
                "id" => $post->getId(),
                "slug" => $post->getSlug()
            ]);

            // related posts
            $relatedPosts = $tablePost->findRandomPosts(3);
            PostHydrate::addImageToAllPosts($tableImage, $relatedPosts);

            // comments of the post
            $comments = $tableComment->findAll();
            $countComments = count($tableComment->findCommentsByPost(["id" => $id]));

            /**
             * @var Comment[]
             */
            $postComments = $tableComment->findCommentsPaginated($post->getId(), 50);  
            CommentHydrate::addUserToComment($tableUser, $tableRole, $postComments);

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
                        ->setContent($_POST["content"]);

                    CommentHydrate::addUserWhenAddComment($tableUser, $tableRole, $comment, $this->session);

                    if ($validator->isValid()) {
                        if ($_POST["id"] === "") {
                            $user = $tableUser->find(["id" => $this->session->read("auth")]);
                            $tableComment->createComment($comment, $post, $user);
                            $this->session->setFlash("Your comment has been added", "success", "mt-5"); // flash message
                            $url = $this->router->url("post", ["id" => $id, "slug" => $slug]) . "?comment=1&create=1";
                        } else {
                            $commentUser = $tableCommentUser->find(["comment_id" => $_POST["id"]]);
                            $user = $tableUser->find(["id" => $commentUser->getUser_id()]);
                            
                            if ($user->getUsername() === $_POST["username"]) {
                                $tableComment->updateComment($comment, $post);
                                $this->session->setFlash("Your comment has been updated", "success", "mt-5"); // flash message
                            } else {
                                $this->session->setFlash("You do not have permission to edit this comment", "danger", "mt-5"); // flash message
                            }
                        }

                        $url = $this->router->url("post", ["id" => $id, "slug" => $slug]) . "?comment=1&update=1";
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