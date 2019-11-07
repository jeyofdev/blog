<!-- flash message -->
<?= $flash; ?>


<!-- view the current post -->
<div class="blog-single mt-50">
    <div class="card">
        <div class="card-header mb-80">
            <img class="img-fluid" src="/img/posts/<?= $post->getImage()->getName(); ?>" alt="">
        </div>

        <div class="card-body mx-50">
            <?php if (jeyofdev\php\blog\Helpers\Categories::getCategories($router, $post) !== "") : ?>
                <p class="mb-30 categories"><?= jeyofdev\php\blog\Helpers\Categories::getCategories($router, $post); ?></p>
            <?php endif; ?>
            <h1 class="card-title mb-35"><?= $post->getName(); ?></h1>            
            <p class="card-text"><?= $post->getFormattedContent(); ?></p>
        </div>
        <div class="card-footer mx-50 mt-30 pt-30">
            <p class="card-muted">
                Written by <a href="<?= $router->url("user", ["id" => $post->getUser()->getId(), "slug" => $post->getUser()->getSlug()]); ?>"><?= $post->getUser()->getUsername(); ?></a>
                on <?= $post->getCreated_at()->format("d F Y"); ?>
            </p>
        </div>
    </div>
</div>


<!-- view the related posts -->
<div class="blog-related mt-125 mx-50">
    <h4 class="text-secondary bloc-title mb-35">You may also like</h4>
    <div class="row">
        <?php foreach ($relatedPosts as $relatedPost) : ?>
            <div class="col-12 col-md-4 mb-0">
                <div class="card">
                    <div class="card-header mb-3">
                        <img class="img-fluid" src="/img/posts/thumbs/<?= $relatedPost->getImage()->getName(); ?>" alt="">
                    </div>

                    <div class="card-body">
                        <a class="d-block card-title" href="<?= $router->url('post', ['id' => $relatedPost->getID(), 'slug' => $relatedPost->getSlug()]); ?>">
                            <h3><?= $relatedPost->getName(); ?></h3>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- view the comments of the post -->
<div class="blog-comment mt-75 mx-50">
    <h4 class="text-secondary bloc-title mb-35"><?= $countComments <= 0 ? "No comments" : $countComments . " comments"; ?></h4>

    <!-- the form to add a new comment -->
    <?php if ($session->exist("auth")) : ?>
        <?= $form->build($url, $buttonLabel); ?>
    <?php else : ?>
        <div class="alert alert-danger">
            <a href="<?= $router->url('login'); ?>">Login to add a comment.</a> 
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($postComments as $comment) : ?>
            <div class="col-12 comment mb-30">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title"><?= $comment->getUsername(); ?></p>
                        <p class="card-text"><?= $comment->getContent(); ?></p>
                    </div>
                </div>

                <?php if ($session->read("auth") === $comment->getUser()->getId()) : ?>
                    <form style="display:inline;" action="<?= $router->url('post', ['id' => $post->getId(), 'slug' => $post->getSlug()]); ?>#formComment" method="post">
                        <input type="hidden" id="id" name="id" value="<?= $comment->getId(); ?>">
                        <input type="hidden" id="username" name="username" value="<?= $comment->getUsername(); ?>">
                        <input type="hidden" id="content" name="content" value="<?= $comment->getContent(); ?>">
                        <button type="submit" class="btn btn-outline-success rounded linkForm">edit</button>
                    </form>
                <?php endif; ?>

                <?php if (jeyofdev\php\blog\Auth\Auth::isAdmin($this->session) || ($session->read("auth") === $comment->getUser()->getId())) : ?>
                    <form style="display:inline;" action="<?= $router->url('comment_delete', ['id' => $comment->getId()]); ?>" method="post" onsubmit="return confirm('Do you really want to delete this comment')">
                        <button type="submit" class="btn btn-outline-danger rounded">delete</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- pagination -->
    <div class="d-flex justify-content-between my-4">
        <?= $pagination->previousLink($link); ?>
        <?= $pagination->nextLink($link); ?>
    </div>
</div>