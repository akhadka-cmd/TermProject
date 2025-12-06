<?php
session_start();
$pageTitle = "Checkout";
require 'includes/header.php';
// Database connection required for transactions
require_once 'db/conn.php';

// 1. Authentication Check
if (!isset($_SESSION['user_id'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables
$cart_items = [];
$subtotal = 0;
$shipping_cost = 15.00; // Flat rate for simulation
$tax_rate = 0.08; // 8% sales tax for simulation
$tax_amount = 0;
$total_price = 0;

// 2. Fetch Cart Items and Calculate Subtotal
try {
    if (!$conn) {
        throw new Exception("Database connection not established.");
    }
    
    $sql = "SELECT c.id as cart_id, p.name, p.price, c.quantity, (p.price * c.quantity) as item_total 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $subtotal += $row['item_total'];
    }
    $stmt->close();

    // Check if cart is empty after fetching
    if (empty($cart_items)) {
        header("Location: cart.php?empty=1"); // Redirect back to cart if empty
        exit();
    }

    // 3. Final Calculations
    $tax_amount = $subtotal * $tax_rate;
    $total_price = $subtotal + $shipping_cost + $tax_amount;
    
} catch (Exception $e) {
    error_log("Checkout Fetch Error: " . $e->getMessage());
    echo "<div class='alert alert-danger container mt-5'>Could not load cart data. Please try again.</div>";
    if (isset($conn)) $conn->close();
    include 'includes/footer.php';
    exit();
}

// 4. Handle Order Submission (SQL Implementation)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
        
    try {
        // 1. INSERT into the orders table
        $insert_order_stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $insert_order_stmt->bind_param("id", $user_id, $total_price);
        $insert_order_stmt->execute();
        
        // Get the ID of the new order record
        $new_order_id = $conn->insert_id;
        $insert_order_stmt->close();

        // 2. CLEAR THE CART (DELETE from cart)
        // This confirms the items have been purchased and are removed from the active cart
        $clear_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $clear_stmt->bind_param("i", $user_id);
        $clear_stmt->execute();
        $clear_stmt->close();
        
        // 3. Redirect to confirmation page with the actual new order ID
        header("Location: confirmation.php?order_id=" . $new_order_id);
        exit();

    } catch (Exception $e) {
        error_log("Order Placement SQL Error: " . $e->getMessage());
        // Handle failure (e.g., redirect back to checkout with an error)
        header("Location: checkout.php?error=db_fail"); 
        exit();
    }
    // --- END: Database Transaction to Place Order ---
}

?>

<div class="container my-5">
    <h1 class="mb-4 text-center">Complete Your Order</h1>

    <div class="row g-4">
        
        <!-- Left Column: Shipping and Payment Form (8 columns on large, 12 on small) -->
        <div class="col-lg-8">
            <form method="POST" action="checkout.php">
                <input type="hidden" name="place_order" value="1">
                
                <!-- Shipping Details -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-truck me-2"></i>Shipping Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                            </div>
                            <div class="col-md-5">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State/Province</label>
                                <select id="state" name="state" class="form-select" required>
                                    <option value="">Choose...</option>
                                    <option value="AB">Alberta</option>
                                    <option value="BC">British Columbia</option>
                                    <option value="MB">Manitoba</option>
                                    <option value="NB">New Brunswick</option>
                                    <option value="NL">Newfoundland and Labrador</option>
                                    <option value="NS">Nova Scotia</option>
                                    <option value="ON">Ontario</option>
                                    <option value="PE">Prince Edward Island</option>
                                    <option value="QC">Quebec</option>
                                    <option value="SK">Saskatchewan</option>
                                    <option value="NT">Northwest Territories</option>
                                    <option value="NU">Nunavut</option>
                                    <option value="YT">Yukon</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="zip" class="form-label">Zip/Postal</label>
                                <input type="text" class="form-control" id="zip" name="zip" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="cardName" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="cardName" placeholder="John A. Doe" required>
                            </div>
                            <div class="col-12">
                                <label for="cardNumber" class="form-label">Credit Card Number</label>
                                <input type="text" class="form-control" id="cardNumber" placeholder="0000 0000 0000 0000" required
                                maxlength="19" oninput="formatCardNumber(this)">
                                <div class="form-text">Max 16 digits, Spaced.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="expiration" class="form-label">Expiration (MM/YY)</label>
                                <input type="text" class="form-control" id="expiration" placeholder="12/26" required
                                maxlength="5" oninput="formatExpiration(this)">
                                <div class="form-text">Format: MM/YY.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" placeholder="123" required
                                maxlength="3" pattern="\d{3}" oninput="formatCVV(this)">
                                <div class="form-text">Max 3 digits.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-lock me-2"></i> Place Order and Pay $<?php echo number_format($total_price, 2); ?>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Right Column: Order Summary (4 columns on large, 12 on small) -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center text-muted">
                            <div><?php echo htmlspecialchars($item['name']); ?> <small> (x<?php echo $item['quantity']; ?>)</small></div>
                            <span class="text-dark">$<?php echo number_format($item['item_total'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between py-2">
                            <span>Subtotal</span>
                            <span class="fw-bold">$<?php echo number_format($subtotal, 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2">
                            <span>Shipping</span>
                            <span class="fw-bold">$<?php echo number_format($shipping_cost, 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-2">
                            <span>Tax (<?php echo $tax_rate * 100; ?>%)</span>
                            <span class="fw-bold">$<?php echo number_format($tax_amount, 2); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between py-3 bg-light">
                            <h5 class="mb-0 text-success">Order Total</h5>
                            <h5 class="mb-0 text-success">$<?php echo number_format($total_price, 2); ?></h5>
                        </li>
                    </ul>
                </div>
            </div>
            <a href="cart.php" class="btn btn-outline-secondary w-100 mt-3">
                <i class="fas fa-arrow-left me-2"></i> Back to Cart
            </a>
        </div>
        
    </div>
</div>

<?php 
if (isset($conn)) {
    $conn->close();
}
require 'includes/footer.php'; 
?>