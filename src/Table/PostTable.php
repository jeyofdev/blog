<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Core\Pagination;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;


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
        protected $table = "post";



        /**
         * The name of the class
         *
         * @var object Instance of type table (ex PostTable)
         */
        protected $className = Post::class;



        /**
         * @var Pagination
         */
        private $pagination;



        /**
         * Get all the posts paginated
         *
         * @return Post[]
         */
        public function findAllPostsPaginated ()
        {
            $sqlPosts = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
            $sqlCount = "SELECT COUNT(id) FROM {$this->table}";
            $this->pagination = new Pagination($this->connection, $sqlPosts, $sqlCount, $this, 6);

            return $this->pagination->getItemsPaginated();
        }



        /**
         * Get the posts paginated by category
         *
         * @param Category $category
         * @return Post[]
         */
        public function findPostsPaginatedByCategory (Category $category)
        {
            $tableAlias = strtolower(substr($this->table, 0, 1));

            $pos = strpos("post_category", "_") + 1;
            $joinAlias = strtolower(substr("post_category", 0, 1) . substr("post_category", $pos, 1));

            $sqlPosts = "SELECT {$tableAlias}.* 
                FROM {$this->table} AS $tableAlias
                JOIN post_category AS $joinAlias ON {$joinAlias}.post_id = {$tableAlias}.id
                WHERE {$joinAlias}.category_id = {$category->getId()}
                ORDER BY created_at DESC
            ";

            $sqlCount = "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}";
            
            $this->pagination = new Pagination($this->connection, $sqlPosts, $sqlCount, $this, 6);

            return $this->pagination->getItemsPaginated($sqlPosts);
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
    }