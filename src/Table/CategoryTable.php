<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Pagination\Pagination;
    use jeyofdev\php\blog\Entity\Category;


    /**
     * Manage the queries of the category table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class CategoryTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "category";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = Category::class;



        /**
         * Get the categories from a post
         *
         * @return Category[]
         */
        public function findCategories (array $params)
        {
            $sql = "SELECT c.*
                FROM post_category AS pc
                JOIN {$this->tableName} AS c ON pc.category_id = c.id
                WHERE pc.post_id = :id
            ";

            $query = $this->prepare($sql, $params);

            return $this->fetchAll($query);
        }



        /**
         * Get the categories corresponding to each post
         *
         * @param string $ids The ids of the posts of the current page
         * @return Categories[]
         */
        public function findCategoriesByPosts (string $ids)
        {
            $sql = "SELECT c.*, pc.post_id
                FROM post_category AS pc
                JOIN {$this->tableName} AS c
                ON c.id = pc.category_id
                WHERE pc.post_id IN ({$ids})
            ";

            $query = $this->query($sql);
            
            return $this->fetchAll($query);
        }



        /**
         * Get all the categories paginated
         *
         * @return Category[]
         */
        public function findAllCategoriesPaginated (int $perPage, string $orderBy = "id", string $direction = "ASC")
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
         * Create a new category
         *
         * @param Category $category
         * @return self
         */
        public function createCategory (Category $category) : self
        {
            $this->create([
                "name" => $category->getName(),
                "slug" => $category->getSlug()
            ]);

            $category->setId((int)$this->connection->lastInsertId());

            return $this;
        }



        /**
         * Update a category
         *
         * @param Category $category
         * @return self
         */
        public function updateCategory (Category $category) : self
        {
            $this->update([
                "name" => $category->getName(),
                "slug" => $category->getSlug()
            ], ["id" => $category->getId()]);

            return $this;
        }



        /**
         * Get the value of a column on all categories
         *
         * @return array
         */
        public function list (string $column) : array
        {
            /**
             * @var Category[]
             */
            $categories = $this->findAll();

            $method = "get" . ucfirst($column);

            $allCategories = [];
            foreach ($categories as $category) {
                $allCategories[] = $category->$method();
            }

            return $allCategories;
        }
    }