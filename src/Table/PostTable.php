<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Exception\RuntimeException;
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
         * @var Pagination
         */
        private $pagination;



        /**
         * The columns of the current entity
         *
         * @var array
         */
        private $columns = [];



        /**
         * The allowed values ​​for the results direction of a query
         */
        const DIRECTION_ALLOWED = ["ASC", "DESC"];



        public function __construct (PDO $connection)
        {
            parent::__construct($connection);
            $this->columns = $this->table
                ->setColumns($this->table)
                ->getColumns();
        }



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
         * Get the value of pagination
         *
         * @return Pagination
         */
        public function getPagination () : Pagination
        {
            return $this->pagination;
        }



        /**
         * Check that a value is allowed in a clause of a query
         *
         * @param mixed $value
         * @return bool|void
         */
        private function checkIfValueIsAllowed (string $clause, $value, array $allowed)
        {
            if (in_array($value, $allowed)) {
                return true;
            } else {
                if ($clause === "orderBy") {
                    throw (new RuntimeException())->columnNotExistInDatabase($value);
                } else if ($clause === "direction") {
                    throw (new RuntimeException())->valueNotAllowed($value, $clause);
                }
            }
        }
    }