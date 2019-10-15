<?php

    namespace jeyofdev\php\blog\Core;


    use Exception;
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
         * @var PostTable
         */
        private $table;



        /**
         * The items of the current page
         *
         * @var array
         */
        private $items = [];



        /**
         * le nombre total d'items
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



        public function __construct (PDO $connection, PostTable $table, int $perPage = 6)
        {
            $this->connection = $connection;
            $this->table = $table;
            $this->perPage = $perPage;
        }



        /**
         * Get the items of the current page
         *
         * @return array
         */
        public function getItemsPaginated () : array
        {
            if (empty($this->items))
            {
                $currentPage = $this->getCurrentPage();
                $nbPages = $this->getPages("id");

                $this->checkIfPageExists($currentPage, $nbPages);
                
                $offset = $this->perPage * ($currentPage -1);

                $this->items = $this->table->findPaginated("created_at", "DESC", $this->perPage, $offset);
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

            return '<a class="btn btn-primary" href="' . $link . '">Previous page</a>';
        }



        /**
         * Generate the link "next page"
         *
         * @return string|null
         */
        public function nextLink (string $link) : ?string
        {
            $currentPage = $this->getCurrentPage();
            $nbPages = $this->getPages("id");

            if ($currentPage >= $nbPages) return null;
            $link .= "?page=" . ($currentPage + 1);

            return '<a class="btn btn-primary ml-auto" href="' . $link . '">Next page</a>';
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
        private function getPages ($column) : int
        {
            if ($this->itemsCount === null) {
                $this->itemsCount = $this->table->countAll($column);
            }
        
            return (int)ceil($this->itemsCount / $this->perPage);
        }



        /**
         * Check if the number of the current page exists
         *
         * @return void
         */
        private function checkIfPageExists (int $currentPage, int $nbPages) : void
        {
            if($currentPage > $nbPages) {
                throw new Exception("This page does not exist");
            }
        }
    }