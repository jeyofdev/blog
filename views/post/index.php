<h1>List of blog posts</h1>


<!-- list of posts -->
<div class="row mt-5">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-6 mb-5">
            <div class="card">
                <div class="card-body">
                    <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>">
                        <h5 class="card-title"><?= $post->getName(); ?></h5>
                    </a>
                    <p class="card-text"><?= $post->getExcerpt(); ?></p>
                    <p class="card-muted">written on <?= $post->getCreated_at()->format("d F Y"); ?></p>
                    <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]); ?>" class="btn btn-primary">see more</a>
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
