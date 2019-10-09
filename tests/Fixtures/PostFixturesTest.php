<?php

    namespace jeyofdev\php\blog\Tests\Fixtures;


    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Fixtures\PostFixtures;
    use PHPUnit\Framework\TestCase;


    final class PostFixturesTest extends TestCase
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
        public function testAddFixtureInTablePost() : void
        {
            $faker = new PostFixtures($this->getDatabase(), "fr_FR");

            $this->assertInstanceOf(PostFixtures::class, $faker->add());
        }
    }