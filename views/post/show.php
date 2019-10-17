<!-- view the current post -->
<h1><?= $post->getName(); ?></h1>
<p class="text-muted"><?= $post->getCreated_At()->format('d F Y'); ?></p>
<div class="categories mb-5">
    <?php foreach ($categories as $k => $category) : ?>
        <?php if ($k > 0) echo ','; ?>
        <a href="<?= $router->url('category', ["id" => $category->getId(), "slug" => $category->getSlug()]); ?>"><?= $category->getName(); ?></a>
    <?php endforeach; ?>
</div>
<p><?= $post->getFormattedContent(); ?></p>