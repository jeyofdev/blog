<h1 class="text-center"><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<!-- feature image -->
<div class="image">
    <?php if (!is_null($image)) : ?>
        <p>Image à la une</p>
        <img src="/img/posts/<?= $image->getName(); ?>" width="150" alt="">
        <form style="display:inline;" action="<?= $router->url('admin_post', ['id' => $post->getId()]); ?>?delete=1" method="post" onsubmit="return confirm('Do you really want to delete this image')">
            <button type="submit" class="btn btn-outline-danger rounded">delete</button>
        </form>
    <?php endif; ?>
</div>


<!-- the form to update a post -->
<?= $form->build($url, "Update", $categories, true, true); ?>
