<?php

    namespace jeyofdev\php\blog\Controller;


    use Exception;
    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Router\Router;
    use PDO;


    /**
     * Manage the controller of the blog
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostController extends Controller
    {
        /**
         * Set the datas of the page which lists the articles of the blog
         *
         * @return void
         */
        public function index (Router $router) : void
        {
            App::getInstance()->setTitle("List of posts");

            $database = new Database("localhost", "root", "root", "php_blog");
            $connection = $database->getConnection("php_blog");

            /**
             * get all posts
             * 
             * @var Post[]
             */
            $query = $connection->query("SELECT * FROM post ORDER BY created_at DESC");
            $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

            /**
             * pagination
             */
            $sqlPostsCount = "SELECT COUNT(id) FROM post";
            $sqlPostsPaginated = "SELECT * FROM post ORDER BY created_at DESC";

            $pagination = new Pagination($connection, $sqlPostsPaginated, $sqlPostsCount, 6);

            /**
             * @var Post[]
             */
            $posts = $pagination->getItems(Post::class);

            // the route of each article
            $link = $router->url("blog");

            $this->render("post.index", compact("posts", "pagination", "router", "link"));
        }



        /**
         * Set the data of the page that displays a post
         *
         * @param Router $router
         * @return void
         */
        public function show (Router $router) : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $connection = $database->getConnection("php_blog");

            // url settings of the current page
            $params = $router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            // get the article from the current page
            $query = $connection->prepare("SELECT * FROM post WHERE id = :id");
            $query->execute(['id' => $id]);
            $query->setFetchMode(PDO::FETCH_CLASS, Post::class);

            /**
             * @var Post|false
             */
            $post = $query->fetch();

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