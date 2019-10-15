<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Post;
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
        protected $table = "post";



        /**
         * The name of the class
         *
         * @var object Instance of type table (ex PostTable)
         */
        protected $className = Post::class;



        /**
         * Get all the posts paginated of the current page
         *
         * @return array
         */
        public function findPaginated (?string $orderBy = null, string $direction = "ASC", ?int $limit = null, ?int $offset = null, ?int $fetchMode = PDO::FETCH_CLASS) : array
        {
            return $this->findAllBy ($orderBy, $direction, $limit, $offset, $fetchMode);
        }
    }