<?php

    namespace jeyofdev\php\blog\Tests\Entity;


    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Manager\EntityManager;
    use PHPUnit\Framework\TestCase;


    final class PostTest extends TestCase
    {
        /**
         * @var Database
         */
        private $database;



        /**
         * @var EntityManager
         */
        private $manager;



        /**
         * @var Post
         */
        private $post;



        public function getDatabase () : Database
        {
            $this->database = new Database("localhost", "root", "root", "php_blog");
            $this->database->create();

            return $this->database;
        }



        /**
         * @test
         */
        public function testAddTable() : void
        {
            $this->manager = new EntityManager($this->getDatabase());
            $this->post = new Post($this->manager);

            $this->assertNotEmpty($this->post);
            $this->assertInstanceOf(Post::class, $this->post->addTable());
        }



        /**
         * @test
         */
        public function testEmptyTable() : void
        {
            $this->manager = new EntityManager($this->getDatabase());
            $this->post = new Post($this->manager);

            $this->assertInstanceOf(Post::class, $this->post->emptyTable());
        }



        /**
         * @test
         */
        public function testDropTable() : void
        {
            $this->manager = new EntityManager($this->getDatabase());
            $this->post = new Post($this->manager);

            $this->assertInstanceOf(Post::class, $this->post->dropTable());
        }
    }

