<?php

    use jeyofdev\php\blog\Database\Database;
    use jeyofdev\php\blog\Entity\Post;
    use jeyofdev\php\blog\Helpers\Helpers;


    // set the title of the page
    $title = "List of posts";


    // connection to the database
    $database = new Database("localhost", "root", "root", "php_blog");
    $connection = $database->getConnection("php_blog");


    // get all posts
    $query = $connection->query("SELECT * FROM post ORDER BY created_at DESC");
    $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
?>


<h1>List of blog posts</h1>


<!-- view the list of articles -->
<div class="row mt-5">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-6 mb-5">
            <div class="card">
                <div class="card-body">
                    <a href="#">
                        <h5 class="card-title"><?= Helpers::e($post->getName()); ?></h5>
                    </a>
                    <p class="card-text"><?= $post->getExcerpt(); ?></p>
                    <p class="card-muted">written on <?= $post->getCreated_at()->format("d F Y"); ?></p>
                    <button class="btn btn-primary">see more</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>