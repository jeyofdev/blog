<!-- cards for the list of posts  -->
<div class="col-12 col-md-6 mb-80">
    <div class="card">
        <div class="card-header mb-35">
            <img class="img-fluid" src="/img/posts/thumbs/<?= $post->getImage()->getName(); ?>" alt="">
        </div>

        <div class="card-body">
            <?php if (jeyofdev\php\blog\Helpers\Categories::getCategories($router, $post) !== "") : ?>
                <p class="mb-30 categories"><?= jeyofdev\php\blog\Helpers\Categories::getCategories($router, $post); ?></p>
            <?php endif; ?>
            <a class="d-block card-title mb-35" href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">
                <h2><?= $post->getName(); ?></h2>
            </a>
            <p class="card-text"><?= $post->getExcerpt(); ?></p>
            <p class="card-muted my-30">
                Written By <a href="<?= $router->url("user", ["id" => $post->getUser()->getId(), "slug" => $post->getUser()->getSlug()]); ?>"><?= $post->getUser()->getUsername(); ?></a>
                on <?= $post->getCreated_at()->format("d F Y"); ?>
            </p>
            <a class="btn btn-primary pagination" href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">see more</a>
        </div>
    </div>
</div>