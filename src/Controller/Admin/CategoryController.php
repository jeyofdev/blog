<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Table\CategoryTable;


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

            $title = App::getInstance()
                ->setTitle("Administration of categories")
                ->getTitle();

            $this->render("admin.category.index", $this->router, compact("categories", "pagination", "link", "title"));
        }
    }