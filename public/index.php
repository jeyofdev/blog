<?php

    use jeyofdev\php\blog\Controller\AppController;
    use jeyofdev\php\blog\Router\Router;
use jeyofdev\php\blog\Url;

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


    // redirection if necessary
    Url::redirect();


    // router
    $router = new Router();
    $router
        // front
        ->get('/', 'home/index', 'home')
        ->get('/blog/', 'post/index', 'blog')
        ->get('/blog/[*:slug]-[i:id]/', 'post/show', 'post')
        ->get('/category/[*:slug]-[i:id]/', 'category/show', 'category')

        // back
        ->get('/admin/', 'admin/post/index', 'admin_posts')
        ->post('/admin/post/delete/[i:id]/', 'admin/post/delete', 'admin_post_delete');


    // controller
    AppController::getInstance()->run($router);