<?php

    namespace jeyofdev\php\blog\Tests\Entity;


    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use PHPUnit\Framework\TestCase;


    final class PostTest extends TestCase
    {
        /**
         * @test
         */
        public function testAddTable() : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $database->create();

            $post = new Post($database);

            $this->assertNotEmpty($post);
            $this->assertInstanceOf(Post::class, $post->addTable());
        }



        /**
         * @test
         */
        public function testDropTable() : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $database->create();

            $post = new Post($database);

            $this->assertInstanceOf(Post::class, $post->addTable()->dropTable());
        }



        /**
         * @test
         */
        public function testEmptyTable() : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $database->create();

            $post = new Post($database);

            $this->assertInstanceOf(Post::class, $post->addTable()->emptyTable());
        }
    }

