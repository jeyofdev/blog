<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Exception\NotFoundException;
    use jeyofdev\php\blog\Router\Router;
    use jeyofdev\php\blog\Url;
    use PDO;


    /**
     * Manage the controller
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractController implements ControllerInterface
    {
        /**
         * @var Router
         */
        protected $router; 



        /**
         * @var PDO
         */
        protected $connection;



        /**
         * The path of views
         * 
         * @var string
         */
        private $viewPath = VIEW_PATH;



        /**
         * @param Router $router
         */
        public function __construct (Router $router)
        {
            $this->router = $router;

            $database = new Database("localhost", "root", "root", "php_blog");
            $this->connection = $database->getConnection("php_blog");
        }



        /**
         * {@inheritDoc}
         */
        public function render (string $view, Router $router, array $datas = []) : void
        {
            ob_start();
            extract($datas);
            require $this->getViewPath() . DIRECTORY_SEPARATOR . str_replace(".", "/", $view) . '.php';
            $content = ob_get_clean();
            require $this->getViewPath() . DIRECTORY_SEPARATOR . 'layout/default.php';
        }



        /**
         * {@inheritDoc}
         */
        public function getViewPath() : string
        {
            return $this->viewPath;
        }



        /**
         * {@inheritDoc}
         */
        public function exists ($table, string $tableName, int $id) : void
        {
            if ($table === false) {
                throw (new NotFoundException())->itemNotFound($tableName, $id);
            }
        }



        /**
         * {@inheritDoc}
         */
        public function checkSlugMatch (Router $router, $table, string $slug, int $id) : void
        {
            $tableName = $table->getTableName();

            if ($table->getSlug() !== $slug) {
                $url = $router->url($tableName, ['slug' => $table->getSlug(), 'id' => $id]);
                Url::redirect(301, $url);
            }
        }
    }