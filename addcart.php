<?php
session_start();
require_once 'db/conn.php';

// 1. Authentication Check
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: loginform.php?redirect=products.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? 'add'; // Default to 'add' if action is missing
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
// Use quantity if provided, otherwise default to 1 for simple add/remove
$quantity_change = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; 

// Ensure basic inputs are valid
if ($product_id <= 0) {
    header("Location: products.php?error=invalid_product");
    exit();
}

try {
    if ($action === 'add') {
        // --- ADD TO CART LOGIC ---
        $check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {

            $new_quantity = $row['quantity'] + $quantity_change;

            $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $new_quantity, $row['id']);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            $insert_qty = max(1, $quantity_change); 
            $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iii", $user_id, $product_id, $insert_qty);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        $check_stmt->close();

        // Redirect to the cart page after successful addition
        header("Location: cart.php?added=success");
        exit();

    } elseif ($action === 'remove') {
        // --- REMOVE FROM CART LOGIC  ---
        
        $remove_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $remove_stmt = $conn->prepare($remove_sql);
        $remove_stmt->bind_param("ii", $user_id, $product_id);
        $remove_stmt->execute();
        $remove_stmt->close();

        // Redirect back to the cart page
        header("Location: cart.php?removed=success");
        exit();

    } elseif ($action === 'update') {
        
        $new_quantity = max(1, $quantity_change); // Ensure quantity is at least 1
        $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Redirect back to the cart page
        header("Location: cart.php?updated=success");
        exit();

    } else {
        // Handle unknown action
        header("Location: products.php?error=unknown_action");
        exit();
    }
} catch (Exception $e) {
    error_log("Cart Action Error: " . $e->getMessage());
    header("Location: products.php?error=db_failure");
    exit();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>