<?php
    // get the categories of the current post
    $categories = array_map(function ($category) use ($router) {
        $url = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getslug()]); 
        return '<a href="' . $url . '">' . $category->getName() . '</a>';
    }, $post->getCategories());

    $categories = implode(", ", $categories);
?>


<!-- view the current post -->
<div class="blog-single mt-75">
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
<div class="blog-related mt-125 mx-80">
    <h4 class="text-secondary bloc-title mb-35">You may also like</h4>
    <div class="row">
        <?php foreach ($relatedPosts as $post) : ?>
            <div class="col-12 col-md-4 mb-0">
                <div class="card">
                    <div class="card-header mb-3">
                        <img class="img-fluid" src="https://via.placeholder.com/500x275" alt="">
                    </div>

                    <div class="card-body">
                        <a class="d-block card-title" href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">
                            <h3><?= $post->getName(); ?></h3>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- view the comments of the post -->
<div class="blog-comment mt-125 mx-80">
    <h4 class="text-secondary bloc-title mb-35"><?= $countComments <= 0 ? "No comments" : $countComments . " comments"; ?></h4>
    <div class="row">
        <?php foreach ($postComments as $comments) : ?>
            <div class="col-12 comment mb-30">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title"><?= $comments->getUsername(); ?></p>
                        <p class="card-text"><?= $comments->getContent(); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- pagination -->
    <div class="d-flex justify-content-between my-4">
        <?= $pagination->previousLink($link); ?>
        <?= $pagination->nextLink($link); ?>
    </div>
</div>