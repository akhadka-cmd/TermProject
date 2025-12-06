<?php 
$pageTitle = "Login";
require 'includes/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <?php if(isset($_GET['registered'])): ?>
            <div class="alert alert-success">Registration successful. Please login.</div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger">Invalid credentials.</div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Login</div>
            <div class="card-body">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>