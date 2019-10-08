<?php

    use jeyofdev\php\blog\Database\Database;


    // Autoload
    require dirname(__DIR__) . '/vendor/autoload.php';


    // php errors
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();


    // initialize the database
    $database = new Database("localhost", "root", "root", "php_blog");
    $database->create();