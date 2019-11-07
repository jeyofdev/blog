<?php

    namespace jeyofdev\php\blog\Controller;


    use jeyofdev\php\blog\App;
    use jeyofdev\php\blog\Hydrate\PostHydrate;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\ImageTable;
    use jeyofdev\php\blog\Table\PostImageTable;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Table\RoleTable;
    use jeyofdev\php\blog\Table\UserTable;


    /**
     * Manage the controller of the authors pages
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class AuthorController extends AbstractController
    {
        /**
         * Get posts by user
         *
         * @return void
         */
        public function show () : void
        {
            $tableUser = new UserTable($this->connection);
            $tableRole = new RoleTable($this->connection);
            $tableCategory = new CategoryTable($this->connection);
            $tablePost = new PostTable($this->connection);
            $tableImage = new ImageTable($this->connection);
            $tablePostImage = new PostImageTable($this->connection);

            // url settings of the current page
            $params = $this->router->getParams();
            $id = (int)$params["id"];
            $slug = $params["slug"];

            /**
             * @var User|false
             */
            $user = $tableUser->find(["id" => $id]);

            // check the category
            $this->exists($user, "user", $id);
            $this->checkSlugMatch($this->router, $user, $slug, $id);
            
            /**
             * @var Post[]
             */
            $posts = $tablePost->findPostsPaginatedByUser($user, 6, "created_at", "desc");

            $firstPost = array_shift($posts);
            PostHydrate::addCategoriesToPost($tableCategory, $firstPost);
            PostHydrate::addUserToPost($tableUser, $tableRole, $firstPost);
            PostHydrate::addImageToPost($tablePostImage, $tableImage, $firstPost);

            if (!empty($posts)) {
                PostHydrate::addCategoriesToAllPosts($tableCategory, $posts);
                PostHydrate::addUserToAllPosts($tableUser, $posts);
                PostHydrate::addImageToAllPosts($tableImage, $posts);
            }

            /**
             * @var Pagination
             */
            $pagination = $tablePost->getPagination();

            // Get the route of the current page
            $link = $this->router->url("user", ['id' => $user->getId(), "slug" => $user->getslug()]);

            $title = App::getInstance()
                ->setTitle("List of posts of : " . $user->getUsername())
                ->getTitle();

            $this->render("author.show", $this->router, $this->session, compact("posts", "firstPost", "pagination", "link", "title"));
        }
    }