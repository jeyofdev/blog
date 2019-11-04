<h1 class="text-center"><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<!-- feature image -->
<div class="image">
    <?php if (!is_null($image)) : ?>
        <p>Image à la une</p>
        <img src="/img/posts/<?= $image->getName(); ?>" width="150" alt="">
    <?php endif; ?>
</div>


<!-- the form to update a post -->
<?= $form->build($url, "Update", $categories, true, true); ?>
