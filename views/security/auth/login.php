<h1 class="text-center"><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<!-- the form to connect to the database -->
<?= $form->build($url, "Log in"); ?>
