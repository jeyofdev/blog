<?php

    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;


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