<!-- view the current article -->
<h1><?= $post->getName(); ?></h1>
<p class="text-muted"><?= $post->getCreated_At()->format('d F Y'); ?></p>
<p><?= $post->getFormattedContent(); ?></p>