<h1><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<!-- the form to add a new post -->
<?= $form->build("Add", $categories); ?>
