<?php 
$pageTitle = "Sign Up | TechStore";
require 'includes/header.php'; 
require_once 'db/conn.php'; 
?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                    <h1 class="h3 mb-0"><i class="fas fa-user-plus me-2"></i>Create Your TechStore Account</h1>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="signup.php" method="POST">
                        
                        <!-- Username Field (Unique Nickname) -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-bold">User Name</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="A unique nickname for login" required>
                        </div>
                        
                        <!-- First Name and Last Name -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label fw-bold">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label fw-bold">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <!-- Password and Confirm Password -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label fw-bold">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <div id="passwordMessage" class="form-text mt-2"></div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">Address</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        
                        <!-- City, Province, Postal Code -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="city" class="form-label fw-bold">City</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="province" class="form-label fw-bold">Province</label>
                                <select class="form-select" id="province" name="province" required>
                                    <option value="" disabled selected>Choose...</option>
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
                            <div class="col-md-4">
                                <label for="postal_code" class="form-label fw-bold">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <!-- CRITICAL FIX: Added id="submitBtn" for passwordcheck.js -->
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane me-2"></i>Register Account</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <p class="mb-0">Already have an account? <a href="loginform.php">Log In here</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php 
require 'includes/footer.php'; 
?>