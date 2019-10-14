<h1>List of blog posts</h1>


<!-- view the list of articles -->
<div class="row mt-5">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-6 mb-5">
            <div class="card">
                <div class="card-body">
                    <a href="#">
                        <h5 class="card-title"><?= jeyofdev\php\blog\Helpers\Helpers::e($post->getName()); ?></h5>
                    </a>
                    <p class="card-text"><?= $post->getExcerpt(); ?></p>
                    <p class="card-muted">written on <?= $post->getCreated_at()->format("d F Y"); ?></p>
                    <button class="btn btn-primary">see more</button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<!-- afficher la pagination -->
<div class="d-flex justify-content-between my-4">
    <?php if ($currentPage > 1) : ?>
        <?php $link = $router->url("blog"); ?>
        <?php if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1); ?>
        <a class="btn btn-primary" href="<?= $link; ?>">Page précédente</a>
    <?php endif; ?>
    <?php if ($currentPage < $pages) : ?>
        <a class="btn btn-primary ml-auto" href="<?= $router->url('blog'); ?>?page=<?= $currentPage + 1; ?>">Page suivante</a>
    <?php endif; ?>
</div>