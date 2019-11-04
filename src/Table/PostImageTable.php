<?php

    namespace jeyofdev\php\blog\Table;


    use jeyofdev\php\blog\Entity\Image;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Entity\PostImage;


    /**
     * Manage the queries of the post_image table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class PostImageTable extends AbstractTable
    {
        /**
         * The name of the table
         *
         * @var string
         */
        protected $tableName = "post_image";



        /**
         * The name of the current class
         *
         * @var string
         */
        protected $className = PostImage::class;



        /**
         * Add a join between a post and an image
         *
         * @param Post $post
         * @param Image $image
         * @return self
         */
        public function createPostImage (Post $post, Image $image) : self
        {
            $this->create([
                "post_id" => $post->getId(),
                "image_id" => !is_null($image->getId()) ? $image->getId() : 1,
            ]);

            return $this;
        }
    }