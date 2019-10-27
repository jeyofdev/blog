<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\PostTable;


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
            $tableCategory = new CategoryTable($this->connection);

            /**
             * @var Post[]
             */
            $posts = $tablePost->findAllPostsPaginated(6, "created_at", "asc");

            // hydrate the posts
            PostHydrate::hydrateAllPosts($tableCategory, $posts);

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
            $tablePost = new PostTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);

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

            // hydrate the posts
            PostHydrate::hydratePost($tableCategory, $post);

            $title = App::getInstance()->setTitle($post->getName())->getTitle();

            $this->render("post.show", $this->router, $this->session, compact("post", "title"));
        }
    }