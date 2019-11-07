<h1 class="text-center"><?= $title; ?></h1>


<!-- list of posts -->
<div class="blog-list mt-35">
    <div class="row mt-5">
        <?php require VIEW_PATH . DS . "parts" . DS . "_firstPostCard.php"; ?>
        <?php foreach ($posts as $post) : ?>
            <?php require VIEW_PATH . DS . "parts" . DS . "_postCard.php"; ?>
        <?php endforeach; ?>
    </div>


    <!-- pagination -->
    <div class="d-flex justify-content-between my-4">
        <?= $pagination->previousLink($link); ?>
        <?= $pagination->nextLink($link); ?>
    </div>
</div>
