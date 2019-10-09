<?php

    namespace jeyofdev\php\blog\Tests\Database;


    use jeyofdev\php\blog\Database\Database;
    use PDO;
    use PHPUnit\Framework\TestCase;


    final class DatabaseTest extends TestCase
    {
        /**
         * @test
         */
        public function testCreateDatabase() : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $database->create();

            $this->assertNotEmpty($database);
        }



        /**
         * @test
         */
        public function testConnectionPDO() : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $connection = $database->getConnection();

            $this->assertInstanceOf(PDO::class, $connection);
        }



        /**
         * @test
         */
        public function testGetDatabaseName() : void
        {
            $database = new Database("localhost", "root", "root", "php_blog");
            $database->create();
            $name = $database->getDb_name();


            $this->assertEquals("php_blog", $name);
        }
    }

