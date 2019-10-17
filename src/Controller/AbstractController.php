<?php

    namespace jeyofdev\php\blog\Controller;


    use Exception;
    use jeyofdev\php\blog\Router\Router;


    /**
     * Manage the controller
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    abstract class AbstractController implements ControllerInterface
    {
        /**
         * The path of views
         * 
         * @var string
         */
        private $viewPath = VIEW_PATH;



        /**
         * {@inheritDoc}
         */
        public function render (string $view, array $datas = []) : void
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
        public function exists ($table, string $message) : void
        {
            if ($table === false) {
                throw new Exception($message);
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
                http_response_code(301);
                header('Location: ' . $url);
                exit();
            }
        }
    }