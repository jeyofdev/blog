<?php

    namespace jeyofdev\php\blog\Entity;


    use DateTime;
    use jeyofdev\php\blog\Helpers\Helpers;
    use jeyofdev\php\blog\Helpers\Text;
    use jeyofdev\php\blog\Manager\EntityManager;


    /**
     * Manage the post table
     * 
     * @author jeyofdev <jgregoire.pro@gmail.com>
     */
    class Post extends AbstractEntity
    {
        /**
         * The columns of the table
         */
        protected $id;
        protected $name;
        protected $slug;
        protected $content;
        protected $created_at;
        protected $updated_at;
        protected $published;


        /**
         * The associated categories to the post
         *
         * @var array
         */
        private $categories = [];



        /**
         * The associated user to the post
         *
         * @var User
         */
        private $user;



        /**
         * The associated image to the post
         *
         * @var Image
         */
        private $image;



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
            $this->setColumnsWithOptions("content", "text", 650000);
            $this->setColumnsWithOptions("created_at", "datetime", null, false, false, false, "DEFAULT CURRENT_TIMESTAMP");
            $this->setColumnsWithOptions("updated_at", "datetime", null, false, false, false, "DEFAULT CURRENT_TIMESTAMP");
            $this->setColumnsWithOptions("published", "tinyint", 1, false, false, false, "DEFAULT 0");

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
         * Get the value of content
         */ 
        public function getContent() : ?string
        {
            return Helpers::e($this->content);
        }



        /**
         * Get the formatted content
         *
         * @return string|null
         */
        public function getFormattedContent (): ?string
        {
            return nl2br(Helpers::e($this->content));
        }



        /**
         * Get the excerpt of content
         *
         * @return string
         */
        public function getExcerpt () : string
        {
            return Helpers::e(Text::excerpt($this->content, 250));
        }



        /**
         * Set the value of content
         *
         * @return  self
         */ 
        public function setContent(string $content) : self
        {
            $this->content = $content;
            return $this;
        }



        /**
         * Get the value of created_at
         */ 
        public function getCreated_at() : DateTime
        {
            return new DateTime($this->created_at);
        }



        /**
         * Set the value of created_at
         *
         * @return  self
         */ 
        public function setCreated_at(string $created_at) : self
        {
            $this->created_at = $created_at;
            return $this;
        }



        /**
         * Get the value of updated_at
         */ 
        public function getUpdated_at() : DateTime
        {
            return new DateTime($this->updated_at);
        }



        /**
         * Set the value of updated_at
         *
         * @return  self
         */ 
        public function setUpdated_at(string $updated_at) : self
        {
            $this->updated_at = $updated_at;
            return $this;
        }



        /**
         * Get the value of published
         */ 
        public function getPublished() : int
        {
            return $this->published;
        }



        /**
         * Set the value of published
         *
         * @return  self
         */ 
        public function setPublished(int $published) : self
        {
            $this->published = $published;
            return $this;
        }



        /**
         * Get the associated categories to the post
         *
         * @return Category[]
         */
        public function getCategories ()
        {
            return $this->categories;
        }



        /**
         * Set the associated categories to the post
         *
         * @return self
         */
        public function setCategories (array $categories = []) : self
        {
            $this->categories = $categories;
            return $this;
        }



        /**
         * Add a category on the post
         *
         * @param Category $category
         * @return self
         */
        public function addCategory (Category $category) : self
        {
            $this->categories[] = $category;
            $category->setPost($this);

            return $this;
        }



        /**
         * Get the ids of the categories
         *
         * @return array
         */
        public function getCategoriesIds () : array
        {
            $ids =[];

            foreach ($this->categories as $category) {
                $ids[] = $category->getID() - 1;
            }

            return $ids;
        }



        /**
         * Get the associated user to the post
         *
         * @return string
         */
        public function getUser ()
        {
            return $this->user;
        }



        /**
         * Add a user on the post
         *
         * @param User $user
         * @return self
         */
        public function addUser (User $user) : self
        {
            $this->user = $user;
            return $this;
        }



        /**
         * Get the associated image to the post
         *
         * @return string
         */
        public function getImage ()
        {
            return $this->image;
        }



        /**
         * Add an image on the post
         *
         * @param Image $image
         * @return self
         */
        public function addImage (Image $image) : self
        {
            $this->image = $image;
            return $this;
        }
    }