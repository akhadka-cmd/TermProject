<?php
session_start();
$pageTitle = "Order History";
require 'includes/header.php';
require_once 'db/conn.php';

// 1. Authentication Check
if (!isset($_SESSION['user_id'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = [];

// 2. Fetch Order History
try {
    $sql = "SELECT id, total_price, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log("Order History Fetch Error: " . $e->getMessage());
    echo "<div class='alert alert-danger mt-5'>Could not load your order history.</div>";
    include 'includes/footer.php';
    exit();
}

?>

<div class="my-5">
    <h1 class="mb-4 text-center">Your Order History</h1>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center shadow-sm p-4 mx-auto" style="max-width: 600px;">
            <i class="fas fa-shopping-bag fa-3x mb-3"></i>
            <p class="lead">You haven't placed any orders yet.</p>
            <a href="products.php" class="btn btn-primary mt-3">Start Shopping Now</a>
        </div>
    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <p class="text-muted text-center">Showing <?php echo count($orders); ?> past orders.</p>
                <div class="list-group">
                    <?php foreach ($orders as $order): ?>
                        <div class="list-group-item list-group-item-action shadow-sm mb-3">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 text-primary">Order #<?php echo htmlspecialchars($order['id']); ?></h5>
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> 
                                    <?php 
                                    // Format the date/time
                                    $date = new DateTime($order['order_date']);
                                    echo $date->format('M j, Y - g:i A'); 
                                    ?>
                                </small>
                            </div>
                            <p class="mb-1">
                                <span class="fw-bold">Total:</span> 
                                <span class="text-success fs-5">$<?php echo number_format($order['total_price'], 2); ?></span>
                            </p>
                            <!-- Placeholder for View Details -->
                            <small class="text-info">
                                <i class="fas fa-info-circle me-1"></i> Note: Full itemized breakdown requires an 'order_items' table.
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>