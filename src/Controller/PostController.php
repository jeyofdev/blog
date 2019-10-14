<?php

    namespace jeyofdev\php\blog\Controller;


    use Exception;
    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
use jeyofdev\php\blog\Router\Router;
use jeyofdev\php\blog\Url;
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
        public function index(Router $router) : void
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

            // check that the current page is valid
            $currentPage = Url::getInt("page", 1);
            Url::getPositiveInt("page", $currentPage);

            // get the total number of items
            $count = (int)$connection
                ->query("SELECT COUNT(id) FROM post")
                ->fetch(PDO::FETCH_NUM)[0];

            // set the number of posts per pages
            $perPage = 6;

            // set the total number of pages
            $pages = ceil($count / $perPage);

            // check that the current page exists
            if($currentPage > $pages) {
                throw new Exception("This page does not exist");
            }

            /**
             * get posts
             * 
             * @var Post[]
             */
            $offset = $perPage * ($currentPage -1);
            $query = $connection->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
            $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

            $this->render("post.index", compact("posts", "currentPage", "pages", "router"));
        }
    }