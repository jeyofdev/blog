<?php

    namespace jeyofdev\php\blog\Entity;


    use DateTime;
    use jeyofdev\php\blog\Database\Database;


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



        /**
         * @param Database $database
         */
        public function __construct(Database $database)
        {
            parent::__construct($database);

            $this->columns = $this
                ->setColumns($this)
                ->getColumns();

            // set the columns of the table
            $this->setColumnsWithOptions("id", "int", 10, false, true, true, false, true);
            $this->setColumnsWithOptions("name", "varchar", 255);
            $this->setColumnsWithOptions("slug", "varchar", 255);
            $this->setColumnsWithOptions("content", "text", 650000);
            $this->setColumnsWithOptions("created_at", "datetime", null, false, false, false, "DEFAULT CURRENT_TIMESTAMP");
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
            return $this->name;
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
            return $this->slug;
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
            return $this->content;
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
    }