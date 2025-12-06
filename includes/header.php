<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Online Computer Store'; ?></title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">TechStore</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
      </ul>
      
      <div class="d-flex align-items-center gap-2">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="order_history.php" class="btn btn-outline-info btn-sm">History</a>
            <a href="cart.php" class="btn btn-outline-light btn-sm">🛒 Cart</a>
            <span class="text-light mx-2">Hi, <?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
            
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <a href="admin/index.php" class="btn btn-warning btn-sm">Admin</a>
            <?php endif; ?>
            
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        <?php else: ?>
            <a href="loginform.php" class="btn btn-outline-light btn-sm">Login</a>
            <a href="signupform.php" class="btn btn-primary btn-sm">Sign Up</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<main class="container">