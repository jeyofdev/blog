<?php

    use jeyofdev\php\blog\Router\Router;


    // Autoload
    require dirname(__DIR__) . '/vendor/autoload.php';


    // php errors
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();


    // constantes
    define("ROOT", dirname(__DIR__));
    define("DEBUG_TIME", microtime(true));
    define("VIEW_PATH", ROOT . DIRECTORY_SEPARATOR . 'views');


    // router
    $router = new Router(VIEW_PATH);
    $router
        ->get('/', 'home/index', 'home')
        ->get('/blog/', 'post/index', 'blog')
        ->run();