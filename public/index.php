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
    Url::redirectToHome();


    // router
    $router = new Router();
    $router
        // 404
        ->match('/404/', 'error/404', '404')

        // front
        ->get('/', 'home/index', 'home')
        ->get('/blog/', 'post/index', 'blog')
        ->match('/blog/[*:slug]-[i:id]/', 'post/show', 'post')

        ->match('/blog/comment/delete/[i:id]/', 'comment/delete', 'comment_delete')

        ->get('/category/[*:slug]-[i:id]/', 'category/show', 'category')

        ->get('/author/[*:slug]-[i:id]/', 'author/show', 'user')

        ->match('/register/', 'security/auth/register', 'register')
        ->match('/login/', 'security/auth/login', 'login')
        ->match('/logout/', 'security/auth/logout', 'logout')

        // back
        ->get('/admin/', 'admin/home/index', 'admin')

        // posts
        ->get('/admin/post/', 'admin/post/index', 'admin_posts')
        ->get('/admin/post/publish/[i:id]/', 'admin/post/publish', 'admin_posts_publish')
        ->match('/admin/post/new/', 'admin/post/new', 'admin_post_new')
        ->match('/admin/post/[i:id]/', 'admin/post/edit', 'admin_post')
        ->post('/admin/post/delete/[i:id]/', 'admin/post/delete', 'admin_post_delete')

        // categories
        ->get('/admin/category/', 'admin/category/index', 'admin_categories')
        ->match('/admin/category/new/', 'admin/category/new', 'admin_category_new')
        ->match('/admin/category/[i:id]/', 'admin/category/edit', 'admin_category')
        ->post('/admin/category/delete/[i:id]/', 'admin/category/delete', 'admin_category_delete');


    // controller
    AppController::getInstance()->run($router);