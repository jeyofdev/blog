<h1 class="text-center"><?= $title; ?></h1>


<!-- flash message -->
<?= $flash; ?>


<a class="btn btn-outline-primary my-5" href="<?= $router->url('admin_category_new'); ?>">Add a new category</a>


<!-- list of categories -->
<?php if (!empty($categories)) : ?>
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
                    <td><?= $category->getName(); ?></td>
                    <td>
                        <a class="btn btn-outline-success rounded" href="<?= $router->url('admin_category', ['id' => $category->getId()]); ?>">Edit</a>
                        <form style="display:inline;" action="<?= $router->url('admin_category_delete', ['id' => $category->getId()]); ?>" method="post" onsubmit="return confirm('Do you really want to delete this category')">
                            <button type="submit" class="btn btn-outline-danger rounded">delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <div class="text-center alert alert-danger">
        <h4 class="text-center">No categories available</h4>
    </div>
<?php endif; ?>


<!-- pagination -->
<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link); ?>
    <?= $pagination->nextLink($link); ?>
</div>
</div>
