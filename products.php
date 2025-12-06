<?php
$pageTitle = "Our Products";
require 'includes/header.php';
require_once 'db/conn.php';

$products = [];
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';

// Fetch products from the database
try {
    $sql = "SELECT id, name, description, price, image_url, category, stock FROM products";
    $params = [];
    $types = '';

    // If a specific category is selected, filter the query
    if ($category_filter !== 'all') {
        $sql .= " WHERE category = ?";
        $params[] = $category_filter;
        $types = 's';
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Product Listing Error: " . $e->getMessage());
    echo "<div class='alert alert-danger mt-5'>Could not load products. Please try again later.</div>";
    $conn->close();
    include 'includes/footer.php';
    exit();
}

// Fetch all distinct categories for the filter dropdown
$categories = [];
try {
    $cat_result = $conn->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");
    while ($row = $cat_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
} catch (Exception $e) {
    error_log("Category Fetch Error: " . $e->getMessage());
}

$conn->close();

?>

<div class="my-5">
    <h1 class="text-center mb-4">Explore Our Latest Tech</h1>

    <!-- Category Filter -->
    <div class="row mb-4 justify-content-end">
        <div class="col-md-4 col-sm-6">
            <label for="categoryFilter" class="form-label visually-hidden">Filter by Category</label>
            <select id="categoryFilter" class="form-select" onchange="window.location.href = 'products.php?category=' + this.value;">
                <option value="all">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                        <?php echo ($category_filter === $cat) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        
        <?php if (empty($products)): ?>
            <div class="col-12 text-center mt-5">
                <div class="alert alert-warning">
                    No products found in this category.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col">
                    <div class="card h-100">
                        <!-- Product Image -->
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>"
                             onerror="this.onerror=null; this.src='https://placehold.co/300x200/505050/ffffff?text=Image+Missing';" style="height: 200px; object-fit: contain;">
                             

                        <div class="card-body d-flex flex-column">
                            <!-- Clickable Product Name (IMPLEMENTS USER REQUEST) -->
                            <h5 class="card-title fw-bold">
                                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="text-dark text-decoration-none">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            
                            <!-- Product Details -->
                            <p class="card-text text-muted small flex-grow-1">
                                <?php echo substr(htmlspecialchars($product['description']), 0, 80); ?>...
                            </p>
                            
                            <p class="card-text fs-4 text-primary fw-bold mb-3">
                                $<?php echo number_format($product['price'], 2); ?>
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <!-- View Details Button (ALSO IMPLEMENTS USER REQUEST) -->
                                <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                                
                                <!-- Add to Cart Button -->
                                <form action="addcart.php" method="POST" class="m-0">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <?php if ($product['stock'] > 0): ?>
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-cart-plus"></i> Add
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-danger" disabled>
                                            Out of Stock
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require 'includes/footer.php'; ?>