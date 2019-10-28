<?php
    // get the categories of each posts
    $categories = array_map(function ($category) use ($router) {
        $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getslug()]); 
        return '<a href="' . $url . '">' . $category->getName() . '</a>';
    }, $post->getCategories());

    $categories = implode(", ", $categories);
?>


<!-- cards for the list of posts  -->
<div class="col-12 col-md-6 mb-100">
    <div class="card">
        <div class="card-header mb-35">
            <img class="img-fluid" src="https://via.placeholder.com/500x225" alt="">
        </div>

        <div class="card-body">
            <?php if ($categories !== "") : ?>
                <p class="mb-30 categories"><?= $categories; ?></p>
            <?php endif; ?>
            <a class="d-block card-title mb-35" href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">
                <h2><?= $post->getName(); ?></h2>
            </a>
            <p class="card-text"><?= $post->getExcerpt(); ?></p>
            <p class="card-muted my-30">
                Written By <?= $post->getUser()->getUsername(); ?>
                on <?= $post->getCreated_at()->format("d F Y"); ?>
            </p>
            <a class="btn btn-primary pagination" href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">see more</a>
        </div>
    </div>
</div>