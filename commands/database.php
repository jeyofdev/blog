<?php

    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Category;
    use jeyofdev\php\blog\Entity\Comment;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Entity\PostCategory;
    use jeyofdev\php\blog\Entity\PostComment;
    use jeyofdev\php\blog\Entity\PostUser;
    use jeyofdev\php\blog\Entity\Role;
    use jeyofdev\php\blog\Entity\User;
    use jeyofdev\php\blog\Entity\UserRole;
    use jeyofdev\php\blog\Fixtures\CategoryFixtures;
    use jeyofdev\php\blog\Fixtures\CommentFixtures;
    use jeyofdev\php\blog\Fixtures\PostCategoryFixtures;
    use jeyofdev\php\blog\Fixtures\PostCommentFixtures;
    use jeyofdev\php\blog\Fixtures\PostFixtures;
    use jeyofdev\php\blog\Fixtures\PostUserFixtures;
    use jeyofdev\php\blog\Fixtures\RoleFixtures;
    use jeyofdev\php\blog\Fixtures\UserFixtures;
    use jeyofdev\php\blog\Fixtures\UserRoleFixtures;
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


    // add the 'role' table
    $role = new Role();
    $role
        ->createColumns($manager)
        ->addTable();


    // add the 'user' table
    $user = new User();
    $user
        ->createColumns($manager)
        ->addTable();


    // add the 'comment' table
    $comment = new Comment();
    $comment
        ->createColumns($manager)
        ->addTable();


    // add the 'post_category' table
    $post_category = new PostCategory();
    $post_category
        ->createColumns($manager)
        ->addTable();


    // add the 'post_comment' table
    $post_comment = new PostComment();
    $post_comment
        ->createColumns($manager)
        ->addTable();


    // add the 'user_role' table
    $user_role = new UserRole();
    $user_role
        ->createColumns($manager)
        ->addTable();


    // add the 'post_user' table
    $post_user = new PostUser();
    $post_user
        ->createColumns($manager)
        ->addTable()
        ->emptyAllTable($post, $category, $role, $user, $comment, $post_category, $post_comment, $user_role, $post_user);


    // Add the fixtures on the 'post' table
    $postFixture = new PostFixtures($manager, $post, "en_US");
    $postFixture->add();


    // Add the fixtures on the 'category' table
    $categoryFixture = new CategoryFixtures($manager, $category, "en_US");
    $categoryFixture->add();


    // Add the fixtures on the 'role' table
    $roleFixture = new RoleFixtures($manager, $role, "en_US");
    $roleFixture->add();


    // Add the fixtures on the 'user' table
    $userFixture = new UserFixtures($manager, $user, "en_US");
    $userFixture->add();


    // Add the fixtures on the 'comment' table
    $commentFixture = new CommentFixtures($manager, $comment, "en_US");
    $commentFixture->add();


    // Add the fixtures on the 'post_category' table
    $post_categoryFixture = new PostCategoryFixtures($manager, $post_category, "en_US", $postFixture, $categoryFixture);
    $post_categoryFixture->add();


    // Add the fixtures on the 'post_comment' table
    $post_commentFixture = new PostCommentFixtures($manager, $post_comment, "en_US", $postFixture, $commentFixture);
    $post_commentFixture->add();


    // Add the fixtures on the 'user_role' table
    $user_roleFixture = new UserRoleFixtures($manager, $user_role, "en_US", $userFixture, $roleFixture);
    $user_roleFixture->add();


    // Add the fixtures on the 'post_user' table
    $post_userFixture = new PostUserFixtures($manager, $post_user, "en_US", $postFixture, $userFixture);
    $post_userFixture->add();
