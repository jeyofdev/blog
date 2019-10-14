<?php

    namespace jeyofdev\php\blog\Core;


    use Exception;
    use jeyofdev\php\blog\Entity\Post;
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
         * Query that get the number of items
         *
         * @var string
         */
        private $queryItemsCount;



        /**
         * Query that get the items of the current page
         *
         * @var string
         */
        private $queryItems;



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



        public function __construct (PDO $connection, string $queryItems, string $queryItemsCount, int $perPage = 6)
        {
            $this->connection = $connection;
            $this->queryItemsCount = $queryItemsCount;
            $this->perPage = $perPage;
            $this->queryItems = $queryItems;
        }



        /**
         * Get the items of the current page
         *
         * @return array
         */
        public function getItems (string $classMapping) : array
        {
            if (empty($this->items))
            {
                $currentPage = $this->getCurrentPage();
                $nbPages = $this->getPages();
                $exist = $this->checkIfPageExists($currentPage, $nbPages);

                $offset = $this->perPage * ($currentPage -1);
                $this->queryItems .= " LIMIT {$this->perPage} OFFSET {$offset}";

                $query = $this->connection->query($this->queryItems);
                $this->items = $query->fetchAll(PDO::FETCH_CLASS, $classMapping);
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
            $nbPages = $this->getPages();

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
        private function getPages () : int
        {
            if ($this->itemsCount === null)
            {
                $this->itemsCount = $this->connection
                ->query($this->queryItemsCount)
                ->fetch(PDO::FETCH_NUM)[0];
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