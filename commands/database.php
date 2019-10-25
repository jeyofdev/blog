<?php

    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Entity\PostCategory;
    use jeyofdev\php\blog\Entity\User;
    use jeyofdev\php\blog\Fixtures\CategoryFixtures;
    use jeyofdev\php\blog\Fixtures\PostCategoryFixtures;
    use jeyofdev\php\blog\Fixtures\PostFixtures;
    use jeyofdev\php\blog\Fixtures\UserFixtures;
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
    $post = new Post();
    $post
        ->createColumns($manager)
        ->addTable();


    // add the 'category' table
    $category = new Category();
    $category
        ->createColumns($manager)
        ->addTable();


    // add the 'post_category' table
    $post_category = new PostCategory();
    $post_category
        ->createColumns($manager)
        ->addTable();


    // add the 'user' table
    $user = new User();
    $user
        ->createColumns($manager)
        ->addTable()
        ->emptyAllTable($post, $category, $post_category);


    // Add the fixtures on the 'post' table
    $postFixture = new PostFixtures($manager, $post, "en_US");
    $postFixture->add();

    // Add the fixtures on the 'category' table
    $categoryFixture = new CategoryFixtures($manager, $category, "en_US");
    $categoryFixture->add();

    // Add the fixtures on the 'post_category' table
    $post_categoryFixture = new PostCategoryFixtures($manager, $post_category, "en_US", $postFixture, $categoryFixture);
    $post_categoryFixture->add();

    // Add the fixtures on the 'user' table
    $userFixture = new UserFixtures($manager, $user, "en_US", $postFixture);
    $userFixture->add();