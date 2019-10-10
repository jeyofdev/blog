<?php

    namespace jeyofdev\php\blog\Tests\Fixtures;


    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Fixtures\PostFixtures;
    use jeyofdev\php\blog\Manager\EntityManager;
    use PHPUnit\Framework\TestCase;


    final class PostFixturesTest extends TestCase
    {
        /**
         * @var Database
         */
        private $database;



        /**
         * @var EntityManager
         */
        private $manager;



        public function getDatabase () : Database
        {
            $this->database = new Database("localhost", "root", "root", "php_blog");
            $this->database->create();
            return $this->database;
        }



        public function createTable () : void
        {
            $this->manager = new EntityManager($this->getDatabase());
            $this->post = new Post($this->manager);
            $this->post->addTable();
        }



        /**
         * @test
         */
        public function testAddFixtureInTablePost() : void
        {
            $this->createTable();
            $faker = new PostFixtures($this->manager, "fr_FR");

            $this->assertInstanceOf(PostFixtures::class, $faker->add());
        }
    }