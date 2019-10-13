<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
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
        public function index() : void
        {
            App::getInstance()->setTitle("List of posts");

            $database = new Database("localhost", "root", "root", "php_blog");
            $connection = $database->getConnection("php_blog");

            // get all posts
            $query = $connection->query("SELECT * FROM post ORDER BY created_at DESC");
            $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

            $this->render("post.index", compact("posts"));
        }
    }