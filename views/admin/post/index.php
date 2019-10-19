<h1 class="text-center"><?= $title; ?></h1>

<!-- List of posts -->
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">name</th>
            <th scope="col">Created_at</th>
            <th scope="col">action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($posts as $post) : ?>
            <tr>
                <th scope="row"><?= $post->getId(); ?></th>
                <td><?= $post->getName(); ?></td>
                <td><?= $post->getCreated_at()->format("d F Y"); ?></td>
                <td>
                    <a class="btn btn-outline-success rounded" href="#">Edit</a>
                    <a class="btn btn-outline-danger rounded" href="#">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<!-- pagination -->
<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link); ?>
    <?= $pagination->nextLink($link); ?>
</div>
</div>
