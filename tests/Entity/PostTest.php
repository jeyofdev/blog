<?php

    namespace jeyofdev\php\blog\Tests\Entity;


    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use PHPUnit\Framework\TestCase;


    final class PostTest extends TestCase
    {
        /**
         * @var Database
         */
        private $database;



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
            $post = new Post($this->getDatabase());

            $this->assertNotEmpty($post);
            $this->assertInstanceOf(Post::class, $post->addTable());
        }



        /**
         * @test
         */
        public function testDropTable() : void
        {
            $post = new Post($this->getDatabase());
            $this->assertInstanceOf(Post::class, $post->addTable()->dropTable());
        }



        /**
         * @test
         */
        public function testEmptyTable() : void
        {
            $post = new Post($this->getDatabase());
            $this->assertInstanceOf(Post::class, $post->addTable()->emptyTable());
        }
    }

