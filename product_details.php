<?php
$pageTitle = "Product Details";
require 'includes/header.php';
require_once 'db/conn.php';

// 1. Get Product ID from URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id <= 0) {
    echo "<div class='alert alert-danger mt-5'>Invalid product selected.</div>";
    include 'includes/footer.php';
    exit();
}

$product = null;

// 2. Fetch Product Data
try {
    $sql = "SELECT id, name, description, price, image_url, category, stock 
            FROM products 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Product Fetch Error: " . $e->getMessage());
    echo "<div class='alert alert-danger mt-5'>Could not load product details.</div>";
    include 'includes/footer.php';
    exit();
}

if (!$product) {
    echo "<div class='alert alert-warning mt-5'>Product not found.</div>";
    include 'includes/footer.php';
    exit();
}

// 3. Display Product Details
?>

<div class="row my-5">
    <div class="col-md-6 mb-4">
        <!-- Product Image -->
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
             alt="<?php echo htmlspecialchars($product['name']); ?>" 
             class="img-fluid rounded shadow-lg" 
             onerror="this.onerror=null; this.src='https://placehold.co/600x400/343a40/ffffff?text=Image+Unavailable';"
             style="object-fit: contain; max-height: 500px; width: 100%;">
    </div>
    
    <div class="col-md-6">
        <!-- Product Info -->
        <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="lead text-primary mb-4">$<?php echo number_format($product['price'], 2); ?></p>
        
        <p class="text-muted"><i class="fas fa-tag me-2"></i>Category: <?php echo htmlspecialchars($product['category']); ?></p>

        <h5 class="mt-4 mb-3">Description</h5>
        <p><?php echo htmlspecialchars($product['description']); ?></p>

        <div class="d-flex align-items-center mb-4">
            <span class="me-3 fw-bold">Availability:</span>
            <?php if ($product['stock'] > 0): ?>
                <span class="badge bg-success fs-6">In Stock (<?php echo $product['stock']; ?> units)</span>
            <?php else: ?>
                <span class="badge bg-danger fs-6">Out of Stock</span>
            <?php endif; ?>
        </div>
        
        <!-- Add to Cart Form (Simplified for demonstration) -->
        <form action="addcart.php" method="POST" class="d-flex align-items-center mt-4">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="action" value="add">
            
            <div class="input-group" style="max-width: 150px;">
                <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg ms-3" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                <i class="fas fa-cart-plus me-2"></i> Add to Cart
            </button>
        </form>
        
        <div class="mt-5">
            <a href="products.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to All Products
            </a>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>