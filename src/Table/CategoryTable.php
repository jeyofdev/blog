<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\PostCategory;
    use PDO;


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
        protected $table = "category";



        /**
         * The name of the class
         *
         * @var object Instance of type table (ex CategoryTable)
         */
        protected $className = Category::class;




        /**
         * Get the categories from a post
         *
         * @return Category[]
         */
        public function findCategories (array $params, int $fetchMode = PDO::FETCH_CLASS)
        {
            $tableJoin = (new PostCategory())->getTableName();

            $sql = "SELECT c.*
                FROM $tableJoin AS pc 
                JOIN {$this->table} AS c ON pc.category_id = c.id
                WHERE pc.post_id = :id
            ";

            $query = $this->prepare($sql, $params, $fetchMode);

            return $this->fetchAll($query);
        }
    }