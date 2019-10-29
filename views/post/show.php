<?php
    // get the categories of the current post
    $categories = array_map(function ($category) use ($router) {
        $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getslug()]); 
        return '<a href="' . $url . '">' . $category->getName() . '</a>';
    }, $post->getCategories());

    $categories = implode(", ", $categories);
?>


<!-- view the current post -->
<div class="blog-single my-75">
    <div class="card">
        <div class="card-header mb-95">
            <img class="img-fluid" src="https://via.placeholder.com/1100x425" alt="">
        </div>

        <div class="card-body mx-80">
            <?php if ($categories !== "") : ?>
                <p class="mb-30 categories"><?= $categories; ?></p>
            <?php endif; ?>
            <h1 class="card-title mb-35"><?= $post->getName(); ?></h1>            
            <p class="card-text"><?= $post->getFormattedContent(); ?></p>
        </div>
        <div class="card-footer mx-80 mt-30 pt-30">
            <p class="card-muted">
                Written by <a href="<?= $router->url("user", ["id" => $post->getUser()->getId(), "slug" => $post->getUser()->getSlug()]); ?>"><?= $post->getUser()->getUsername(); ?></a>
                on <?= $post->getCreated_at()->format("d F Y"); ?>
            </p>
        </div>
    </div>
</div>


<!-- view the related posts -->
<div class="blog-related mt-75">
    <div class="row">
        <?php foreach ($relatedPosts as $post) : ?>
            <div class="col-12 col-md-4 mb-5">
                <div class="card">
                    <div class="card-header">
                        <img class="img-fluid" src="https://via.placeholder.com/1100x425" alt="">
                    </div>

                    <div class="card-body">
                        <a class="d-block card-title" href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">
                            <h3><?= $post->getName(); ?></h3>
                        </a>
                        <p class="card-text"><?= $post->getExcerpt(); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>