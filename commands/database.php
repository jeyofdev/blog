<?php

    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Fixtures\PostFixtures;


    // Autoload
    require dirname(__DIR__) . '/vendor/autoload.php';


    // php errors
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();


    // initialize the database
    $database = new Database("localhost", "root", "root", "php_blog");
    $database->create();


    // add the 'post' table
    $post = new Post($database);
    $post->addTable();
    $post->emptyTable();


    // Add the fixtures on the 'post' table
    $faker = new PostFixtures($database, "fr_FR");
    $faker->add();