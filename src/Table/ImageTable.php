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
    }