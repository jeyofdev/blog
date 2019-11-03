<?php

    namespace jeyofdev\php\blog\Image;


    use jeyofdev\php\blog\Entity\Image as EntityImage;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Table\ImageTable;


    /**
     * Manage the upload of an image
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Image {

        /**
         * @var EntityImage
         */
        private $image;



        /**
         * The image datas to upload
         *
         * @var array
         */
        private $upload = [];



        /**
         * The extension of the image
         *
         * @var string
         */
        private $extension;



        /**
         * The errors of the image to upload
         *
         * @var array
         */
        private $error = [];



        /**
         * The allowed image extensions
         */
        const ALLOWED_EXTENSIONS = ["jpg", "jpeg", "png"];



        /**
         * Save the image on the server 
         * and add its name in the database
         *
         * @param Post $post
         * @param ImageTable $tableImage
         * @return void
         */
        public function addImage (Post $post, ImageTable $tableImage) : void
        {
            $file = $_FILES["image"];
            $this->upload = [
                "name" => $file["name"],
                "tmp_name" => $file["tmp_name"]
            ];

            $this->image = new EntityImage();

            if ($this->upload["name"] !== "") {
                if ($this->checkExtensionIsValid()) {
                    $this->image->setName($post->getSlug() . "-001." . $this->extension);
                    $tableImage->addImage($this->image);
    
                    move_uploaded_file($this->upload["tmp_name"], IMAGE . DS . "posts" . DS . $this->image->getName());
                } else {
                    $this->error["extension"] = true;
                }
            } else {
                $image = $tableImage->find(["id" => 1]);
                $this->image->setName($image->getName());
            }
        }



        /**
         * Check that the extension of the image is valid
         *
         * @return boolean
         */
        private function checkExtensionIsValid () : bool
        {
            $this->extension = strtolower(pathinfo($this->upload["name"], PATHINFO_EXTENSION));

            if (in_array($this->extension, self::ALLOWED_EXTENSIONS)) {
                return true;
            }

            return false;
        }



        /**
         * Get the current image
         *
         * @return EntityImage|null
         */
        public function getImage () : ?EntityImage
        {
            return $this->image;
        } 



        /**
         * Get the errors of the image to upload
         *
         * @return array
         */
        public function getError () : array
        {
            return $this->error;
        }



        /**
         * Convert the array of the allowed extensions to a string
         *
         * @return string
         */
        public static function getAllowedExtensions () : string
        {
            return implode(", ", self::ALLOWED_EXTENSIONS);
        }
    }