<h1 class="text-center"><?= $title; ?></h1>


<!-- list of categories -->
<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($categories as $category) : ?>
            <tr>
                <th scope="row"><?= $category->getId(); ?></th>
                <td>
                    <a href="<?= $router->url('category', ["id" => $category->getId()]); ?>"><?= $category->getName(); ?></a>
                </td>
                <td>
                    <a class="btn btn-outline-info rounded" href="<?= $router->url('category', ['id' => $category->getId()]); ?>">View</a>
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
