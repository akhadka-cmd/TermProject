<?php 
$pageTitle = "Home";
require 'includes/header.php'; 
?>

<div class="p-5 mb-4 bg-white rounded-3 shadow-sm border text-center">
    <h1 class="display-4 fw-bold">Welcome to TechStore</h1>
    <p class="fs-5 text-muted">Your one-stop shop for high-performance computers and accessories.</p>
    <a href="products.php" class="btn btn-primary btn-lg mt-3">Browse Products</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="h-100 p-4 bg-light border rounded-3 text-center">
            <h3>Graphic Cards</h3>
            <p>The latest GPUs for extreme gaming and demanding creative workloads.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="h-100 p-4 bg-light border rounded-3 text-center">
            <h3>Desktops</h3>
            <p>Custom built towers for maximum power.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="h-100 p-4 bg-light border rounded-3 text-center">
            <h3>Accessories</h3>
            <p>Keyboards, mice, and more.</p>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>