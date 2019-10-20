<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Table\PostTable;
    use PDO;


    /**
     * Manage the controller of the posts in the admin
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
         * Set the datas of the page which lists the posts of the blog in the admin
         *
         * @return void
         */
        public function index (Router $router) : void
        {
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
            $link = $router->url("admin_posts");

            // flash message
            $flash = null;
            if (isset($_GET["delete"])) {
                $flash = '<div class="alert alert-success my-5">L\'article a bien été supprimé</div>';
            }

            $title = App::getInstance()
                ->setTitle("Administration of posts")
                ->getTitle();

            $this->render("admin.post.index", compact("posts", "pagination", "router", "link", "title", "flash"));
        }



        /**
         * delete a post
         *
         * @return void
         */
        public function delete (Router $router) : void
        {
            $tablePost = new PostTable($this->connection);

            // url settings of the current page
            $params = $router->getParams();
            $id = (int)$params["id"];

            $tablePost->delete(["id" => $id]);

            // redirect to the home of the admin
            $url = $router->url("admin_posts") . "?delete=1";
            http_response_code(301);
            header("Location: " . $url);
            exit();
        }
    }