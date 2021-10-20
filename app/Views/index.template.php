<?php require_once 'Partials/header.template.php'?>

    <h2 class="m-2">Welcome to Product Catalog</h2>

    <a class="btn btn-success m-2" href="/products/create" role="button">Create a new product</a>

    <h3 class="m-2">All products catalog</h3>

    <form class="m-2" method="get" action="/search">
        <div class="m-2">
            <label class="mr-sm-2" for="inlineFormCustomSelect">Search by category</label>
            <select class="custom-select d-block" id="category" name="category">
                <option value="" selected>All</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->getId() ?>">
                        <?php echo $category->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="m-2">
            <button type="submit" name="search" class="btn btn-primary">Search</button>
        </div>
    </form>

    <table class="table m-2">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">Tags</th>
                <th scope="col">Amount</th>
                <th scope="col">Added</th>
                <th scope="col">Edited</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product->getTitle() ?></td>
                    <td><?php echo array_search($product->getCategory(), $this->categories) ?></td>
                    <td>tags go here</td>
                    <td><?php echo $product->getAmount() ?></td>
                    <td><?php echo $product->getCreatedAt() ?></td>
                    <td><?php echo $product->getUpdatedAt() ?></td>
                    <td>
                        <form action="/products/<?php echo $product->getId() ?>/edit" method="get">
                            <button type="submit" class="btn btn-secondary">Edit</button>
                        </form>
                    </td>
                    <td>
                        <form action="/products/<?php echo $product->getId() ?>/delete" method="post"
                              onSubmit="return confirm('Do you really want to delete this product?');">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php require_once 'Partials/footer.template.php' ?>