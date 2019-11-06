<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Image;


    /**
     * Manage the queries of the image table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class ImageTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "image";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = Image::class;



        /**
         * Add a new image
         *
         * @param Image $image
         * @return self
         */
        public function addImage (Image $image) : self
        {
            $this->create([
                "name" => $image->getName()
            ]);

            $image->setId((int)$this->connection->lastInsertId());

            return $this;
        }



        /**
         * Get the image of each posts
         *
         * @param string $ids
         * @return array
         */
        public function findImageByPosts (string $ids) : array
        {
            $sql = "SELECT i.*, pi.post_id
                FROM post_image AS pi
                JOIN {$this->tableName} AS i
                ON i.id = pi.image_id
                WHERE pi.post_id IN ({$ids})
            ";

            $query = $this->query($sql);
            
            return $this->fetchAll($query);
        }
    }