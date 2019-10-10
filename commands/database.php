<?php

    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Fixtures\PostFixtures;
    use jeyofdev\php\blog\Manager\EntityManager;


    // Autoload
    require dirname(__DIR__) . '/vendor/autoload.php';


    // php errors
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();


    // initialize the database
    $database = new Database("localhost", "root", "root", "php_blog");
    $database->create();


    // initialize the entity Manager
    $manager = new EntityManager($database);


    // add the 'post' table
    $post = new Post($manager);
    $post->addTable();
    $post->emptyTable();
    // $post->dropTable();


    // Add the fixtures on the 'post' table
    $faker = new PostFixtures($manager, "fr_FR");
    $faker->add();