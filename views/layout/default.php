<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>My personal blog</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>

    <body class="d-flex flex-column vh-100">
        <diV class="container text-center">
            <?= $content; ?>
        </diV>

        <!-- loading time of the page -->
        <?php if (defined("DEBUG_TIME")) : ?>
            <footer class="bg-dark py-4 px-4 mt-auto footer">
                <p class="text-white my-0">Page generated in <?= round(1000 * (microtime(true) - DEBUG_TIME)); ?> milliseconds.</p>
            </footer>
        <?php endif; ?>
    </body>
</html>