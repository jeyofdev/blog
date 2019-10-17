<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\PostCategory;


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
        public function findCategories (array $params)
        {
            $table = (new PostCategory())->getTableName();
            $pos = strpos($table, "_") + 1;
            $tableAlias = strtolower(substr($table, 0, 1) . substr($table, $pos, 1));

            $joinAlias = strtolower(substr($this->table, 0, 1));

            $sql = "SELECT {$joinAlias}.*
                FROM $table AS $tableAlias 
                JOIN {$this->table} AS $joinAlias ON $tableAlias.category_id = {$joinAlias}.id
                WHERE $tableAlias.post_id = :id
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
            $table = (new PostCategory())->getTableName();
            $pos = strpos($table, "_") + 1;
            $tableAlias = strtolower(substr($table, 0, 1) . substr($table, $pos, 1));

            $joinAlias = strtolower(substr($this->table, 0, 1));


            $sql = "SELECT {$joinAlias}.*, {$tableAlias}.post_id
            FROM $table AS $tableAlias
            JOIN category AS {$joinAlias}
            ON {$joinAlias}.id = {$tableAlias}.category_id
            WHERE {$tableAlias}.post_id IN ({$ids})
            ";

            $query = $this->query($sql);
            
            return $this->fetchAll($query);
        }
    }