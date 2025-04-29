<?php
require_once 'config.php';
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = trim($_POST['user-name']);
    $password = bin2hex(random_bytes(8)); // Generate temporary password
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                // Generate verification token
                $token = bin2hex(random_bytes(32));
                $token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
                
                // Insert user
                $stmt = $pdo->prepare("INSERT INTO users (email, password, verification_token, token_expiry, created_at) 
                                      VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$email, $hashed_password, $token, $token_expiry]);
                
                // Send verification email (in production)
                // $verification_link = "https://yourdomain.com/verify.php?token=$token";
                // mail($email, "Verify Your Account", "Click here to verify: $verification_link");
                
                // Store in session for demo purposes
                $_SESSION['temp_email'] = $email;
                $_SESSION['temp_password'] = $password;
                $_SESSION['temp_token'] = $token;
                
                // Redirect to success page
                header("Location: registration-success.php");
                exit();
            }
        } catch(PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en" dir="ltr" class="landing-pages">
<!-- [Your existing HTML head section] -->
<body class="body-bg landing-pages">
    <!-- [Your loader section] -->
    <main class="main-content">
        <div class="sign-in-page position-relative">
            <div class="container">
                <div class="row justify-content-center align-items-center height-self-center h-100">
                    <div class="col-lg-5 col-md-12 align-self-center">
                        <div class="sign-user_card position-relative bg-white mx-auto">
                            <!-- [Your logo section] -->
                            
                            <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            
                            <form method="post">
                                <div class="custom-form-field">
                                    <input type="email" name="user-name" placeholder="Your email id *" class="form-control mb-5" required>
                                </div>
                                <p class="mb-5">Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our <a href="privacy-policy.html">privacy policy</a>.</p>
                                <button type="submit" class="iq-button text-capitalize border-0">
                                    <span class="iq-btn-text-holder position-relative">register</span>
                                    <span class="iq-btn-icon-holder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 8 8" fill="none">
                                            <path d="M7.32046 4.70834H4.74952V7.25698C4.74952 7.66734 4.41395 8 4 8C3.58605 8 3.25048 7.66734 3.25048 7.25698V4.70834H0.679545C0.293423 4.6687 0 4.34614 0 3.96132C0 3.5765 0.293423 3.25394 0.679545 3.21431H3.24242V0.673653C3.28241 0.290878 3.60778 0 3.99597 0C4.38416 0 4.70954 0.290878 4.74952 0.673653V3.21431H7.32046C7.70658 3.25394 8 3.5765 8 3.96132C8 4.34614 7.70658 4.6687 7.32046 4.70834Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                            <div class="d-flex align-items-center mt-3">
                                <p class="my-0">Already have an account?</p>
                                <h5 class="sign_up_btn mb-0 ms-2">
                                    <div class="iq-btn-container">
                                        <a class="iq-button iq-btn-link text-capitalize" href="login.php">
                                            sign in
                                            <span class="btn-link-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 8 8" fill="none">
                                                    <path d="M7.32046 4.70834H4.74952V7.25698C4.74952 7.66734 4.41395 8 4 8C3.58605 8 3.25048 7.66734 3.25048 7.25698V4.70834H0.679545C0.293423 4.6687 0 4.34614 0 3.96132C0 3.5765 0.293423 3.25394 0.679545 3.21431H3.24242V0.673653C3.28241 0.290878 3.60778 0 3.99597 0C4.38416 0 4.70954 0.290878 4.74952 0.673653V3.21431H7.32046C7.70658 3.25394 8 3.5765 8 3.96132C8 4.34614 7.70658 4.6687 7.32046 4.70834Z" fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- [Rest of your HTML] -->
</body>
</html>