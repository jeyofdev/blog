<!-- cards for the first post  -->
<div class="col-12 mb-80">
    <div class="card">
        <div class="card-header mb-35">
            <img class="img-fluid" src="/img/posts/<?= $firstPost->getImage()->getName(); ?>" alt="">
        </div>

        <div class="card-body">
            <?php if (jeyofdev\php\blog\Helpers\Categories::getCategories($router, $firstPost) !== "") : ?>
                <p class="mb-30 categories"><?= jeyofdev\php\blog\Helpers\Categories::getCategories($router, $firstPost); ?></p>
            <?php endif; ?>
            <a class="d-block card-title mb-35" href="<?= $router->url('post', ['id' => $firstPost->getID(), 'slug' => $firstPost->getSlug()]); ?>">
                <h2><?= $firstPost->getName(); ?></h2>
            </a>
            <p class="card-text"><?= $firstPost->getExcerpt(); ?></p>
            <p class="card-muted my-30">
                Written By <a href="<?= $router->url("user", ["id" => $firstPost->getUser()->getId(), "slug" => $firstPost->getUser()->getSlug()]); ?>"><?= $firstPost->getUser()->getUsername(); ?></a>
                on <?= $firstPost->getCreated_at()->format("d F Y"); ?>
            </p>
            <a class="btn btn-primary pagination" href="<?= $router->url('post', ['id' => $firstPost->getID(), 'slug' => $firstPost->getSlug()]); ?>">see more</a>
        </div>
    </div>
</div>