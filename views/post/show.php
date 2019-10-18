<!-- view the current post -->
<div class="blog-single my-75">
    <div class="card">
        <div class="card-header mb-95">
            <img class="img-fluid" src="https://via.placeholder.com/1100x425" alt="">
        </div>

        <div class="card-body mx-80">
            <div class="mb-30 categories">
                <?php foreach ($post->getCategories() as $k => $category) : ?>
                    <?php if ($k > 0) echo ','; ?>
                    <a href="<?= $router->url('category', ["id" => $category->getId(), "slug" => $category->getSlug()]); ?>"><?= $category->getName(); ?></a>
                <?php endforeach; ?>
            </div>
            <h1 class="card-title mb-35"><?= $post->getName(); ?></h1>            
            <p class="card-text"><?= $post->getFormattedContent(); ?></p>
        </div>
        <div class="card-footer mx-80 mt-30 pt-30">
            <p class="card-muted">Written on <?= $post->getCreated_At()->format('d F Y'); ?></p>
        </div>
    </div>
</div>