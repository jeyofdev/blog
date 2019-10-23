<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>My personal blog | Admin - <?= isset($title) ? "$title" : null; ?></title>

        <link rel="stylesheet" href="http://localhost:8080/assets/css/app.css">
    </head>

    <body class="d-flex flex-column">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                        <a class="nav-link" href="<?= $router->url("home"); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->url("admin"); ?>">Admin Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->url("admin_posts"); ?>">Posts</a>
                    </li>
                </ul>
            </div>
        </nav>

        <diV class="container mt-5">
            <?= $content; ?>
        </div>

        <!-- loading time of the page -->
        <?php if (defined("DEBUG_TIME")) : ?>
            <footer class="bg-dark py-4 px-4 mt-auto footer">
                <p class="text-white my-0">Page generated in <?= round(1000 * (microtime(true) - DEBUG_TIME)); ?> milliseconds.</p>
            </footer>
        <?php endif; ?>

        <script src="http://localhost:8080/assets/js/app.js"></script>
    </body>
</html>