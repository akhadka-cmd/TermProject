<?php
session_start();
require_once 'db/conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Data Retrieval and Sanitization ---
    $fname = htmlspecialchars($_POST['first_name'] ?? '');
    $lname = htmlspecialchars($_POST['last_name'] ?? '');
    $username = htmlspecialchars($_POST['username'] ?? ''); 
    $email = htmlspecialchars($_POST['email'] ?? '');
    $address = htmlspecialchars($_POST['address'] ?? '');
    $city = htmlspecialchars($_POST['city'] ?? '');
    $province = htmlspecialchars($_POST['province'] ?? '');
    $postalcode = htmlspecialchars($_POST['postal_code'] ?? '');
    $raw_password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? ''; 

    $error_message = null;

    // --- Validation Checks ---
    if (empty($fname) || empty($lname) || empty($username) || empty($email) || empty($raw_password) || empty($address) || empty($city) || empty($province) || empty($postalcode)) {
        $error_message = "Error: All fields are required. Please go back and try again.";
    } elseif ($raw_password !== $confirm_password) {
        $error_message = "Error: Passwords do not match. Please go back and try again.";
    }

    if ($error_message) {
        // Output the error and exit gracefully
        echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
        goto end_script;
    }

    $hashedPassword = password_hash($raw_password, PASSWORD_DEFAULT);

    // --- Check for existing user (Username or Email) ---
    try {
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            echo '<div class="alert alert-warning" role="alert">Error: A user with that Username or Email already exists. Please choose another one.</div>';
            goto end_script;
        }
    } catch (Exception $e) {
        error_log("Database error during user check: " . $e->getMessage());
        echo '<div class="alert alert-danger" role="alert">A database error occurred during verification. Please try again later.</div>';
        goto end_script;
    }


    // --- Insert New User ---
    try {
        $sql = "INSERT INTO users (first_name, last_name, username, email, password, address, city, province, postal_code) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("sssssssss", 
            $fname,
            $lname,
            $username, 
            $email, 
            $hashedPassword, 
            $address, 
            $city, 
            $province, 
            $postalcode
        );

        if ($stmt->execute()) {
            // Success: Redirect to login page
            header('Location: loginform.php?registration=success');
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Error: Registration failed: ' . $conn->error . '</div>';
        }

        $stmt->close();
        
    } catch (Exception $e) {
        error_log("Database error during registration: " . $e->getMessage());
        echo '<div class="alert alert-danger" role="alert">Registration failed due to a database error. Please contact support.</div>';
    }
} else {
    echo '<div class="alert alert-info" role="alert">Form submission error. Please access the form via signupform.php.</div>';
}

end_script:
if (isset($conn)) {
    $conn->close();
}
?>