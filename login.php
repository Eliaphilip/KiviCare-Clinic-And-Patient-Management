<?php
require_once 'config.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    try {
        $stmt = $pdo->prepare("SELECT id, email, password, is_verified FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                if ($user['is_verified']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    // Update last login
                    $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")
                        ->execute([$user['id']]);
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Please verify your email first";
                }
            } else {
                $error = "Invalid credentials";
            }
        } else {
            $error = "Invalid credentials";
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
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
                                    <input type="email" name="email" placeholder="Your email id *" class="form-control mb-3" required>
                                </div>
                                <div class="custom-form-field">
                                    <input type="password" name="password" placeholder="Password *" class="form-control mb-3" required>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">Remember me</label>
                                    </div>
                                    <a href="forgot-password.php" class="text-primary">Forgot password?</a>
                                </div>
                                <button type="submit" class="iq-button text-capitalize border-0 w-100">
                                    <span class="iq-btn-text-holder position-relative">Login</span>
                                    <span class="iq-btn-icon-holder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 8 8" fill="none">
                                            <path d="M7.32046 4.70834H4.74952V7.25698C4.74952 7.66734 4.41395 8 4 8C3.58605 8 3.25048 7.66734 3.25048 7.25698V4.70834H0.679545C0.293423 4.6687 0 4.34614 0 3.96132C0 3.5765 0.293423 3.25394 0.679545 3.21431H3.24242V0.673653C3.28241 0.290878 3.60778 0 3.99597 0C4.38416 0 4.70954 0.290878 4.74952 0.673653V3.21431H7.32046C7.70658 3.25394 8 3.5765 8 3.96132C8 4.34614 7.70658 4.6687 7.32046 4.70834Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                            <div class="d-flex align-items-center mt-3">
                                <p class="my-0">Don't have an account?</p>
                                <h5 class="sign_up_btn mb-0 ms-2">
                                    <div class="iq-btn-container">
                                        <a class="iq-button iq-btn-link text-capitalize" href="register.php">
                                            Register
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