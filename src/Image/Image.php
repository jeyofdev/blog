<?php

    namespace jeyofdev\php\blog\Image;


    use jeyofdev\php\blog\Entity\Image as EntityImage;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Entity\PostImage;
    use jeyofdev\php\blog\Table\ImageTable;
    use jeyofdev\php\blog\Table\PostImageTable;


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
         * Delete the image of a post from the server and the database
         *
         * @param Post $post
         * @param PostImageTable $tablePostImage
         * @param ImageTable $tableImage
         * @return void
         */
        public static function deleteImage (Post $post, PostImageTable $tablePostImage, ImageTable $tableImage) : void
        {
            /**
             * @var PostImage
             */
            $postImage = $tablePostImage->find(["post_id" => $post->getId()]);
            
            $imageId = $postImage->getImage_id();

            if ($imageId !== 1) {
                /**
                 * @var Image
                 */
                $image = $tableImage->find(["id" => $imageId]);

                unlink(IMAGE . DS . "posts" . DS . $image->getName());
                $tableImage->delete(["id" => $imageId]);
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
         * Get the id of the image from the post_image join table
         *
         * @param PostImageTable $tablePostImage
         * @param Post $post
         * @return int
         */
        public static function getImageIdOfPost (PostImageTable $tablePostImage, Post $post) : int
        {
            /**
             * @var PostImage
             */
            $postImage = $tablePostImage->find(["post_id" => $post->getId()]);
            $imageId = $postImage->getImage_id();

            return $imageId;
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