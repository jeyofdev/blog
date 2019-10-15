<?php

    namespace jeyofdev\php\blog\Controller;


    use Exception;
    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Table\PostTable;
    use PDO;


    /**
     * Manage the controller of the blog
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostController extends Controller
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
         * Set the datas of the page which lists the articles of the blog
         *
         * @return void
         */
        public function index (Router $router) : void
        {
            App::getInstance()->setTitle("List of posts");

            $tablePost = new PostTable($this->connection);
            $pagination = new Pagination($this->connection, $tablePost, 6);

            /**
             * Get the posts of the current page
             * 
             * @var Post[]
             */
            $posts = $pagination->getItemsPaginated();

            // the route of each article
            $link = $router->url("blog");

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

            // url settings of the current page
            $params = $router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            /**
             * get the post of the current page
             * 
             * @var Post|false
             */
            $post = $tablePost->find(["id" => $id]);

            // check that the article exists
            if ($post === false) {
                throw new Exception("No article matches");
            }
        
            // check that the slug of the url corresponds to the slug of the current article
            if ($post->getSlug() !== $slug) {
                $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
                http_response_code(301);
                header('Location: ' . $url);
                exit();
            }

            $this->render("post.show", compact("post"));
        }
    }