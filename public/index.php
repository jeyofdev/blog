<?php
    
    // Autoload
    require dirname(__DIR__) . '/vendor/autoload.php';


    // constantes
    define("DEBUG_TIME", microtime(true));


    // php errors
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();


    // loading time of the page
    if (defined("DEBUG_TIME")) {
        dump("Page générée en " . round(1000 * (microtime(true) - DEBUG_TIME)) . " millisecondes.");
    }