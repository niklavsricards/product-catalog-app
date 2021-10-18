<?php require_once 'app/Views/Partials/header.template.php' ?>

    <h4>Sign in</h4>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger m-4 w-25" role="alert">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <form class="w-25 m-4" method="post" action="/login">
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required
                   value="<?php echo $email ?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required
                   value="<?php echo $password ?>">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Login</button>
        <small class="d-block">Don't have an account? <a href="/register">Register here</a></small>
    </form>

<?php require_once 'app/Views/Partials/footer.template.php' ?>