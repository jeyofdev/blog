<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Pagination\Pagination;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\ImageTable;
    use jeyofdev\php\blog\Table\PostImageTable;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;


    /**
     * Manage the controller of the categories
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CategoryController extends AbstractController
    {
        /**
         * Get posts by category
         *
         * @return void
         */
        public function show () : void
        {
            $tableCategory = new CategoryTable($this->connection);
            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);
            $tablePost = new PostTable($this->connection);
            $tableImage = new ImageTable($this->connection);
            $tablePostImage = new PostImageTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            /**
             * @var Category|false
             */
            $category = $tableCategory->find(["id" => $id]);

            // check the category
            $this->exists($category, "category", $id);
            $this->checkSlugMatch($this->router, $category, $slug, $id);
            
            /**
             * @var Post[]
             */
            $posts = $tablePost->findPostsPaginatedByCategory($category, 6, "created_at", "desc");
            $firstPost = array_shift($posts);

            PostHydrate::hydrateAllPostsPaginated($posts, $firstPost, $tableCategory, $tableUser, $tableRole, $tableImage, $tablePostImage);

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // Get the route of the current page
            $link = $this->router->url("category", ['id' => $category->getId(), "slug" => $category->getslug()]);

            $title = App::getInstance()
                ->setTitle($category->getName(), "List of posts of the category : ")
                ->getTitle();

            $this->render("category.show", $this->router, $this->session, compact("posts", "firstPost", "pagination", "link", "title"));
        }
    }