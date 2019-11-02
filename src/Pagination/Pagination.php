<?php

    namespace jeyofdev\php\blog\Pagination;


    use jeyofdev\php\blog\Exception\NotFoundException;
    use jeyofdev\php\blog\Table\CategoryTable;
    use jeyofdev\php\blog\Table\PostTable;
    use jeyofdev\php\blog\Url;
    use PDO;


    /**
     * Manage a pagination
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Pagination
    {
        /**
         * @var PDO
         */
        private $connection;



        /**
         * Query that retrieves the datas
         *
         * @var string
         */
        private $queryDatas;



        /**
         * Query that define the number of result to retrieve
         *
         * @var string
         */
        private $queryCount;



        /**
         * @var PostTable|CategoryTable
         */
        private $table;



        /**
         * The items of the current page
         *
         * @var array
         */
        private $items = [];



        /**
         * The total number of items
         *
         * @var int
         */
        private $itemsCount;



        /**
         * Set the number of items to display per page
         *
         * @var int
         */
        private $perPage;



        /**
         * @param PostTable|CategoryTable $table
         * @param integer $perPage
         */
        public function __construct (PDO $connection, string $queryDatas, string $queryCount, $table, int $perPage = 6)
        {
            $this->connection = $connection;
            $this->queryDatas = $queryDatas;
            $this->queryCount = $queryCount;
            $this->table = $table;
            $this->perPage = $perPage;
        }



        /**
         * Get the items of the current page
         *
         * @return array
         */
        public function getItemsPaginated (array $params = []) : array
        {
            if (empty($this->items))
            {
                $currentPage = $this->getCurrentPage();
                $nbPages = $this->getPages($params);

                $this->checkIfPageExists($currentPage, $nbPages);
                
                $offset = $this->perPage * ($currentPage -1);

                $query = $this->table->prepare($this->queryDatas . " LIMIT {$this->perPage} OFFSET $offset", $params);
                $this->items = $query->fetchAll();
            }

            return $this->items;
        }



        /**
         * Generate the link "previous page"
         *
         * @return string|null
         */
        public function previousLink (string $link) : ?string
        {
            $currentPage = $this->getCurrentPage();

            if ($currentPage <= 1) return null;

            if ($currentPage > 2) {
                $link .= "?page=" . ($currentPage - 1);
            }

            return '<a class="btn btn-outline-primary" href="' . $link . '">Previous page</a>';
        }



        /**
         * Generate the link "next page"
         *
         * @return string|null
         */
        public function nextLink (string $link) : ?string
        {
            $currentPage = $this->getCurrentPage();
            $nbPages = $this->getPages();

            if ($currentPage >= $nbPages) return null;
            $link .= "?page=" . ($currentPage + 1);

            return '<a class="btn btn-outline-primary ml-auto" href="' . $link . '">Next page</a>';
        }



        /**
         * Get the number of the current page
         *
         * @return integer
         */
        private function getCurrentPage () : int
        {
            return Url::getPositiveInt("page", 1);
        }



        /**
         * Get the number of pages
         *
         * @return integer
         */
        private function getPages (array $params = []) : int
        {
            if ($this->itemsCount === null) {
                $query = $this->table->prepare($this->queryCount, $params, PDO::FETCH_NUM);
                $this->itemsCount = $query->fetch()[0];
            }
        
            $nbPage = (int)ceil($this->itemsCount / $this->perPage);
        
            return ($nbPage !== 0) ? $nbPage : 1;
        }



        /**
         * Check if the number of the current page exists
         *
         * @return void
         */
        private function checkIfPageExists (int $currentPage, int $nbPages) : void
        {
            if($currentPage > $nbPages) {
                throw (new NotFoundException())->pageNotFound();
            }
        }
    }