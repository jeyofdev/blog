<?php

    namespace jeyofdev\php\blog\Table;


    use DateTime;
    use DateTimeZone;
    use jeyofdev\php\blog\Pagination\Pagination;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Entity\PostCategory;
    use jeyofdev\php\blog\Entity\User;
    use PDO;


    /**
     * Manage the queries of the post table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = Post::class;



        /**
         * @var DateTimeZone
         */
        private $timeZone;



        /**
         * Get all the posts paginated
         *
         * @return Post[]
         */
        public function findAllPostsPaginated (int $perPage, string $orderBy = "id", string $direction = "ASC")
        {
            $direction = strtoupper($direction);

            if ($this->checkIfValueIsAllowed("orderBy", $orderBy, $this->columns)) {
                if ($this->checkIfValueIsAllowed("direction", $direction, self::DIRECTION_ALLOWED)) {
                    $sqlPosts = "SELECT * FROM {$this->tableName} ORDER BY $orderBy $direction";
                    $sqlCount = "SELECT COUNT(id) FROM {$this->tableName}";
                    $this->pagination = new Pagination($this->connection, $sqlPosts, $sqlCount, $this, $perPage);

                    return $this->pagination->getItemsPaginated();
                }
            }
        }



        /**
         * Get the posts paginated by category
         *
         * @param Category $category
         * @return Post[]
         */
        public function findPostsPaginatedByCategory (Category $category, int $perPage, string $orderBy = "id", string $direction = "ASC")
        {
            $direction = strtoupper($direction);

            if ($this->checkIfValueIsAllowed("orderBy", $orderBy, $this->columns)) {
                if ($this->checkIfValueIsAllowed("direction", $direction, self::DIRECTION_ALLOWED)) {
                    $tableAlias = strtolower(substr($this->tableName, 0, 1));

                    $pos = strpos("post_category", "_") + 1;
                    $joinAlias = strtolower(substr("post_category", 0, 1) . substr("post_category", $pos, 1));

                    $sqlPosts = "SELECT {$tableAlias}.* 
                        FROM {$this->tableName} AS $tableAlias
                        JOIN post_category AS $joinAlias ON {$joinAlias}.post_id = {$tableAlias}.id
                        WHERE {$joinAlias}.category_id = {$category->getId()}
                        ORDER BY $orderBy $direction
                    ";

                    $sqlCount = "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}";
                    
                    $this->pagination = new Pagination($this->connection, $sqlPosts, $sqlCount, $this, $perPage);

                    return $this->pagination->getItemsPaginated($sqlPosts);
                }
            }
        }



        /**
         * Get the posts from a user
         *
         * @param User $user
         * @return Post[]
         */
        public function findPostsPaginatedByUser (User $user, int $perPage, string $orderBy = "id", string $direction = "ASC")
        {
            $direction = strtoupper($direction);

            if ($this->checkIfValueIsAllowed("orderBy", $orderBy, $this->columns)) {
                if ($this->checkIfValueIsAllowed("direction", $direction, self::DIRECTION_ALLOWED)) {
                    $sqlPosts = "SELECT p.* 
                        FROM post AS p
                        JOIN post_user AS pu
                        ON pu.post_id = p.id
                        WHERE pu.user_id = {$user->getId()}
                        ORDER BY $orderBy $direction
                    ";
                    $sqlCount = "SELECT COUNT(user_id) FROM post_user WHERE user_id = {$user->getId()}";

                    $this->pagination = new Pagination($this->connection, $sqlPosts, $sqlCount, $this, $perPage);

                    return $this->pagination->getItemsPaginated($sqlPosts);
                }
            }
        }



        /**
         * Create a new post
         *
         * @param Post $post
         * @return self
         */
        public function createPost (Post $post, string $timeZone = "Europe/Paris") : self
        {
            $createdAt = $this->getCurrentDate($post, $timeZone);

            $this->create([
                "name" => $post->getName(),
                "slug" => $post->getSlug(),
                "content" => $post->getContent(),
                "created_at" => $createdAt
            ]);

            $post->setId((int)$this->connection->lastInsertId());

            $this->attachCategories($post->getID(), ["post_id" => $post->getID(), "category_id" => $_POST["categoriesIds"]]);


            return $this;
        }



        /**
         * Update a post
         *
         * @param Post $post
         * @return self
         */
        public function updatePost (Post $post, string $timeZone = "Europe/Paris") : self
        {
            $updatedAt = $this->getCurrentDate($post, $timeZone);

            $this
                ->update([
                    "name" => $post->getName(),
                    "slug" => $post->getSlug(),
                    "content" => $post->getContent(),
                    "updated_at" => $updatedAt
                ], ["id" => $post->getId()]);

            $this->attachCategories($post->getID(), ["post_id" => $post->getID(), "category_id" => $_POST["categoriesIds"]]);

            return $this;
        }



        /**
         * Delete the categories related to the current article
         * and add the new categories to the current article
         *
         * @return self
         */
        public function attachCategories (int $postId, array $params, int $fetchMode = PDO::FETCH_CLASS) : self
        {
            $tableName = (new PostCategory)->getTableName();

            $this->delete(["post_id" => $postId], $tableName);

            $set = $this->setQueryParams($params);

            $sql = "INSERT INTO $tableName SET $set[0]";
            $query = $this->connection->prepare($sql);
            foreach ($params["category_id"] as $category) {
                $query->execute([
                    "post_id" => $postId,
                    "category_id" => $category + 1
                ]);
                $this->setFetchMode($fetchMode);
            }

            return $this;
        }



        /**
         * Initialize a date with the current date
         *
         * @param Post $post
         * @return string
         */
        private function getCurrentDate (Post $post, $timeZone = "Europe/Paris") : string
        {
            $this->timeZone = new DateTimeZone($timeZone);
            $currentDate = (new DateTime("now", $this->timeZone))->format("Y-m-d H:i:s");

            return $post
                ->setUpdated_at($currentDate)
                ->getUpdated_at()
                ->format("Y-m-d H:i:s");
        }
    }