<?php
session_start();
// Include the database connection setup. This defines the $conn variable.
require_once 'db/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and collect input data
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check for empty fields and redirect
    if (empty($email) || empty($password)) {
        header('Location: loginform.php?error=empty_fields');
        exit();
    }

    try {
        // Prepare the statement to retrieve user data, INCLUDING is_admin and first_name
        $stmt = $conn->prepare("SELECT id, username, first_name, password, is_admin FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        // Get the result set
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Fetch the user data
            $user = $result->fetch_assoc();
            $db_password = $user['password'];

            // Verify the password
            if (password_verify($password, $db_password)) {
                
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                
                // CRITICAL ADMIN STEP: Save the admin status to the session
                $_SESSION['is_admin'] = $user['is_admin']; 

                // Redirect based on Admin status
                if ($_SESSION['is_admin'] == 1) {
                    // Redirect Administrator to the Admin Dashboard
                    header('Location: admin/index.php');
                } else {
                    // Redirect regular user to the main homepage
                    header('Location: index.php?login=success');
                }
                exit();
                
            } else {
                // Invalid password
                header('Location: loginform.php?error=invalid_credentials');
                exit();
            }
        } else {
            // User not found
            header('Location: loginform.php?error=invalid_credentials');
            exit();
        }

    } catch (Exception $e) {
        error_log("Database error during login: " . $e->getMessage());
        header('Location: loginform.php?error=db_error');
        exit();
    } finally {
        if (isset($stmt)) $stmt->close();
    }
} else {
    // If accessed without POST data
    header('Location: loginform.php');
    exit();
}

// Close the connection
if (isset($conn)) {
    $conn->close();
}
?>