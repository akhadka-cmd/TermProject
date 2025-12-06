<?php
session_start();
$pageTitle = "Order Confirmed";
require 'includes/header.php';

$order_id = htmlspecialchars($_GET['order_id'] ?? 'N/A');

// Ensure user is logged in before viewing confirmation
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

?>

<div class="container my-5 text-center">
    <div class="card shadow-lg p-5 mx-auto" style="max-width: 600px;">
        <!-- Font Awesome check icon -->
        <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
        <h1 class="card-title text-success mb-3">Order Placed Successfully!</h1>
        <p class="lead">Thank you for your purchase. Your order is being processed.</p>

        
        <!-- Navigation Buttons -->
        <div class="mt-4">
            <a href="products.php" class="btn btn-primary btn-lg me-3">Continue Shopping</a>
            <a href="index.php" class="btn btn-outline-secondary btn-lg">Go to Homepage</a>
        </div>
    </div>
</div>

<?php 
require 'includes/footer.php'; 
?>