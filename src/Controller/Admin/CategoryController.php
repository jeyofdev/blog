<?php

    namespace jeyofdev\php\blog\Controller\Admin;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Controller\AbstractController;
    use jeyofdev\php\blog\Core\Auth;
    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Form\CategoryForm;
    use jeyofdev\php\blog\Form\Validator\CategoryValidator;
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
            // check that the user is logged in
            Auth::isConnect($this->router);

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
                $flash = '<div class="alert alert-success my-5">The category has been deleted</div>';
            } else if (isset($_GET["create"])) {
                $flash = '<div class="alert alert-success my-5">The post has been created</div>';
            }

            $title = App::getInstance()
                ->setTitle("Administration of categories")
                ->getTitle();

            $this->render("admin.category.index", $this->router, compact("categories", "pagination", "link", "title", "flash"));
        }



        /**
         * Add a new category
         *
         * @return void
         */
        public function new () : void
        {
            // check that the user is logged in
            Auth::isConnect($this->router);

            $tableCategory = new CategoryTable($this->connection);

            $errors = []; // form errors

            // check that the form is valid and update the post
            $validator = new CategoryValidator("en", $_POST, $tableCategory);

            if ($validator->isSubmit()) {
                $category = new Category();
                $category
                    ->setName($_POST["name"])
                    ->setSlug($_POST["slug"]);

                if ($validator->isValid()) {
                    $tableCategory->createCategory($category);

                    $url = $this->router->url("admin_categories", ["id" => $category->getId()]) . "?create=1";
                    Url::redirect(301, $url);
                } else {
                    $errors = $validator->getErrors();
                }
            }

            // form
            $form = new CategoryForm($_POST, $errors);

            // url of the current page
            $url = $this->router->url("admin_category_new");

            // flash message
            $flash = null;
            if (!empty($errors)) {
                $flash = '<div class="alert alert-danger my-5">The catégorie could not be created</div>';
            }

            $title = App::getInstance()
                ->setTitle("Add a new category")
                ->getTitle();

            $this->render("admin.category.new", $this->router, compact("form", "url", "title", "flash"));
        }



        /**
         * Update a category
         *
         * @return void
         */
        
        public function edit () : void
        {
            // check that the user is logged in
            Auth::isConnect($this->router);

            $tableCategory = new CategoryTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];

            /**
             * @var Category|null
             */
            $category = $tableCategory->find(["id" => $id]);

            $success = false; // query success
            $errors = []; // form errors

            // check that the form is valid and update the category
            $validator = new CategoryValidator("en", $_POST, $tableCategory, $category->getId());

            if ($validator->isSubmit()) {
                $category
                    ->setName($_POST["name"])
                    ->setSlug($_POST["slug"]);

                if ($validator->isValid()) {
                    $tableCategory->updateCategory($category);
                    $success = true;
                } else {
                    $errors = $validator->getErrors();
                }
            }

            // form
            $form = new CategoryForm($category, $errors);

            // url of the current page
            $url = $this->router->url("admin_category", ["id" => $id]);

            // flash message
            $flash = null;
            if ($success) {
                $flash = '<div class="alert alert-success my-5">The category has been updated</div>';
            }

            if (!empty($errors)) {
                $flash = '<div class="alert alert-danger my-5">The category could not be updated</div>';
            }

            $title = App::getInstance()
                ->setTitle("Edit the category with the id : $id")
                ->getTitle();

            $this->render("admin.category.edit", $this->router, compact("form", "url", "title", "flash"));
        }



        /**
         * Delete a post
         *
         * @return void
         */
        public function delete () : void
        {
            // check that the user is logged in
            Auth::isConnect($this->router);

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