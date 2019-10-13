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