<h1 class="text-center">List of blog posts</h1>


<!-- list of posts -->
<div class="blog-list mt-75">
    <?php if (!empty($posts)) : ?>
        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <?php require VIEW_PATH . DIRECTORY_SEPARATOR . "parts" . DIRECTORY_SEPARATOR . "_postCard.php"; ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="text-center alert alert-danger">
            <h4 class="text-center">No posts available</h4>
        </div>
    <?php endif; ?>


    <!-- pagination -->
    <div class="d-flex justify-content-between my-4">
        <?= $pagination->previousLink($link); ?>
        <?= $pagination->nextLink($link); ?>
    </div>
</div>
