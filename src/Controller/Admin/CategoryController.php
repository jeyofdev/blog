<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Url;


    /**
     * Manage the categories in the admin
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CategoryController extends AbstractController
    {
        /**
         * List the categories
         *
         * @return void
         */
        public function index () : void
        {
            $tableCategory = new CategoryTable($this->connection);

            /**
             * @var Category[]
             */
            $categories = $tableCategory->findAllCategoriesPaginated(5, "id", "desc");

            /**
             * @var Pagination
             */
            $pagination = $tableCategory->getPagination();

            // get the route of the current page
            $link = $this->router->url("admin_categories");

            // flash message
            $flash = null;
            if (isset($_GET["delete"])) {
                $flash = '<div class="alert alert-success my-5">The post has been deleted</div>';
            }

            $title = App::getInstance()
                ->setTitle("Administration of categories")
                ->getTitle();

            $this->render("admin.category.index", $this->router, compact("categories", "pagination", "link", "title", "flash"));
        }



        /**
         * Delete a post
         *
         * @return void
         */
        public function delete () : void
        {
            $tableCategory = new CategoryTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            $tableCategory->delete(["id" => $id]);

            // redirect to the home of the admin
            $url = $this->router->url("admin_categories") . "?delete=1";
            Url::redirect(301, $url);
        }
    }