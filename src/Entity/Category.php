<?php

    namespace jeyofdev\php\blog\Entity;


    use jeyofdev\php\blog\Helpers\Helpers;
    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the category table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Category extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $id;
        protected $name;
        protected $slug;


        /**
         * The id of the post associated with the current category
         *
         * @var int
         */
        private $post_id;



        /**
         * The post associated with the current category
         *
         * @var Post
         */
        private $post;



        /**
         * @param EntityManager $manager
         */
        public function createColumns(EntityManager $manager) : self
        {
            parent::createColumns($manager);

            // set the columns of the table
            $this->setColumnsWithOptions("id", "int", 10, false, true, true, null, true);
            $this->setColumnsWithOptions("name", "varchar", 255);
            $this->setColumnsWithOptions("slug", "varchar", 255);

            return $this;
        }



        /**
         * Get the value of id
         */ 
        public function getId() : ?int
        {
            return $this->id;
        }



        /**
         * Set the value of id
         *
         * @return self
         */ 
        public function setId(int $id) : self
        {
            $this->id = $id;
            return $this;
        }



        /**
         * Get the value of name
         */ 
        public function getName() : ?string
        {
            return Helpers::e($this->name);
        }



        /**
         * Set the value of name
         *
         * @return  self
         */ 
        public function setName(string $name) : self
        {
            $this->name = $name;
            return $this;
        }



        /**
         * Get the value of slug
         */ 
        public function getSlug() : ?string
        {
            return Helpers::e($this->slug);
        }



        /**
         * Set the value of slug
         *
         * @return  self
         */ 
        public function setSlug(string $slug) : self
        {
            $this->slug = $slug;
            return $this;
        }



        /**
         * Get the id of the post associated with the current category
         *
         * @return integer|null
         */
        public function getPost_Id () : ?int
        {
            return $this->post_id;
        }


        /**
         * Set the post associated with the current category
         *
         * @param Post $post
         * @return void
         */
        public function setPost (Post $post) : self
        {
            $this->post = $post;
            return $this;
        }
    }