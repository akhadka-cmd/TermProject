<?php 
$pageTitle = "My Cart";
require 'includes/header.php';
require_once 'db/conn.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- Handle "Remove Item" Logic ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'remove') {
    $cart_id_to_remove = intval($_POST['cart_id']);
    
    $delete_stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $delete_stmt->bind_param("ii", $cart_id_to_remove, $user_id);
    
    if ($delete_stmt->execute()) {
        echo "<div class='alert alert-warning alert-dismissible fade show container mt-3'>Item removed from cart. <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    $delete_stmt->close();
}

$sql = "SELECT c.id as cart_id, p.name, p.price, p.image_url, c.quantity, (p.price * c.quantity) as subtotal 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['subtotal'];
}
$stmt->close();
?>

<div class="container mt-5">
    <h1 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Your Shopping Cart</h1>

    <?php if (count($cart_items) > 0): ?>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col" class="text-end pe-4">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cart_items as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="Product" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        <span class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill"><?php echo $item['quantity']; ?></span>
                                </td>
                                <td class="fw-bold text-primary">$<?php echo number_format($item['subtotal'], 2); ?></td>
                                <td class="text-end pe-4">
                                    <form action="cart.php" method="POST" onsubmit="return confirm('Are you sure you want to remove this item?');">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Item">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card-footer bg-white py-4">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <a href="products.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4 class="mb-3">Total: <span class="text-success">$<?php echo number_format($total_price, 2); ?></span></h4>
                        <a href="checkout.php" class="btn btn-success btn-lg">
                            Proceed to Checkout <i class="fas fa-credit-card ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-basket fa-4x text-muted"></i>
            </div>
            <h3 class="text-muted">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added anything yet.</p>
            <a href="products.php" class="btn btn-primary btn-lg">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

<?php 
if (isset($conn)) {
    $conn->close();
}
require 'includes/footer.php'; 
?>