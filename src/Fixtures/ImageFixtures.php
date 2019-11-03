<?php

    namespace jeyofdev\php\blog\Fixtures;


    /**
     * Add the fixtures in the 'image' table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class ImageFixtures extends AbstractFixtures
    {
        /**
         * The id of the image
         *
         * @var int
         */
        private $imageId;



        /**
         * {@inheritDoc}
         */
        public function add () : self
        {
            $this->entity->setName("default.jpg");
            $this->manager->insert($this->entity);
            $this->imageId = $this->manager->lastInsertId();

            return $this;
        }



        /**
         * Get the value of imageId
         *
         * @return int
         */
        public function getImageId () : ?int
        {
            return $this->imageId;
        }
    }