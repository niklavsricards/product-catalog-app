<?php require_once 'app/Views/Partials/header.template.php' ?>

    <h3 class="m-2">Add a new product to the catalog</h3>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger m-4 w-25" role="alert">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form class="w-25 m-4" method="post" action="/products/create">
        <div class="form-group m-2">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Enter product title" required>
        </div>

        <div class="form-group m-2 col-md-6">
            <label for="category">Category</label>
            <select class="custom-select d-block" id="category" name="category">
                <option selected>Choose...</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->getId() ?>">
                    <?php echo $category->getName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-md-4 m-2">
            <label for="amount">Amount</label>
            <input type="number" min="0" class="form-control" id="amount" name="amount"
                   placeholder="Enter amount" required>
        </div>
        <button type="submit" class="btn btn-success m-2">Add</button>
    </form>

<?php require_once 'app/Views/Partials/footer.template.php' ?>