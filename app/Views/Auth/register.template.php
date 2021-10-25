<?php use App\Session;

require_once 'app/Views/Partials/header.template.php' ?>

    <h4 class="m-4">Register a new account</h4>

    <?php if (Session::errors()): ?>
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <div class="alert alert-danger m-4 w-25" role="alert">
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form class="w-25 m-4" method="post" action="/register">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required
                   value="<?php echo $name ?>">
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required
                   value="<?php echo $email ?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required
                   value="<?php echo $password ?>">
        </div>
        <div class="form-group">
            <label for="passwordConfirm">Confirm Password</label>
            <input type="password" class="form-control" id="passwordConfirm"
                   name="passwordConfirm" placeholder="Confirm your password" required
                   value="<?php echo $passwordConfirm ?>">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Register</button>
        <small class="d-block">Already have an account? <a href="/login">Login here</a></small>
    </form>

<?php require_once 'app/Views/Partials/footer.template.php' ?>

