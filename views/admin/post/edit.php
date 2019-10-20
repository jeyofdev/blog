<h1 class="text-center"><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<!-- The form -->
<form action="" method="post" class="my-5">
    <div class="form-group">
        <label for="name">Title</label>
        <input type="text" class="form-control <?= (array_key_exists('name', $errors)) ? 'is-invalid' : null; ?>" id="name" name="name" value="<?= $post->getName(); ?>">
        <?php if (array_key_exists("name", $errors)) : ?>
            <div class="invalid-feedback">
                <?= implode("<br>", $errors["name"]); ?>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>