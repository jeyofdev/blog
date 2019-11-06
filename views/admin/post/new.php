<h1 class="text-center"><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<!-- the form to add a new post -->
<?= $form->build($url, "Add", $categories); ?>
