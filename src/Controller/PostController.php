<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Hydrate\Hydrate;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\PostTable;
    use PDO;


    /**
     * Manage the controller of the posts
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostController extends AbstractController
    {
        /**
         * @var PDO
         */
        private $connection;



        public function __construct ()
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $this->connection = $database->getConnection("php_blog");
        }



        /**
         * Set the datas of the page which lists the posts of the blog
         *
         * @return void
         */
        public function index (Router $router) : void
        {
            $tablePost = new PostTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);

            /**
             * @var Post[]
             */
            $posts = $tablePost->findAllPostsPaginated(6, "created_at", "asc");

            // hydrate the posts
            Hydrate::hydrateAllPosts($tableCategory, $posts);

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // the route of each post
            $link = $router->url("blog");

            App::getInstance()->setTitle("List of posts");

            $this->render("post.index", compact("posts", "pagination", "router", "link"));
        }



        /**
         * Set the datas of the page that displays a post
         *
         * @param Router $router
         * @return void
         */
        public function show (Router $router) : void
        {
            $tablePost = new PostTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);

            // url settings of the current page
            $params = $router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            /**
             * @var Post|false
             */
            $post = $tablePost->find(["id" => $id]);

            // check the post
            $this->exists($post, "post", $id);
            $this->checkSlugMatch($router, $post, $slug, $id);

            // hydrate the posts
            Hydrate::hydratePost($tableCategory, $post);

            $title = App::getInstance()->setTitle($post->getName())->getTitle();

            $this->render("post.show", compact("post", "router", "title"));
        }
    }