<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Hydrate\Hydrate;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\PostTable;
    use PDO;


    /**
     * Manage the controller of the categories
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    
    class CategoryController extends AbstractController
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
         * Get posts by category
         *
         * @param Router $router
         * @return void
         */
        public function show (Router $router) : void
        {
            $tableCategory = new CategoryTable($this->connection);
            $tablePost = new PostTable($this->connection);

            // url settings of the current page
            $params = $router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            /**
             * @var Category|false
             */
            $category = $tableCategory->find(["id" => $id]);

            // check the category
            $this->exists($category, "No category matches");
            $this->checkSlugMatch($router, $category, $slug, $id);
            
            /**
             * @var Post[]
             */
            $posts = $tablePost->findPostsPaginatedByCategory($category);

            // hydrate the posts
            Hydrate::hydratePosts($tableCategory, $posts);

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // set the route links to each post
            $link = $router->url("category", ['id' => $category->getId(), "slug" => $category->getslug()]);

            $title = App::getInstance()
                ->setTitle($category->getName(), "List of posts of the category : ")
                ->getTitle();

            $this->render("category.show", compact("posts", "router", "pagination", "link", "title"));
        }
    }