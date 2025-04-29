<?php
// Start session and include config
session_start();
require_once '../includes/config.php';

// Initialize variables
$error = '';
$success = '';

// Process form if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_login = trim($_POST['user_login']);
    
    try {
        // Check if user exists by email or username
        $stmt = $pdo->prepare("SELECT id, email FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$user_login, $user_login]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Generate reset token (valid for 1 hour)
            $reset_token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Insert or update reset token
            $stmt = $pdo->prepare("REPLACE INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$user['id'], $reset_token, $expires_at]);
            
            // In production, you would send an email here
            // $reset_link = "https://yourdomain.com/auth/new-password.php?token=$reset_token";
            // mail($user['email'], "Password Reset Request", "Click here to reset: $reset_link");
            
            // For demo purposes, store in session
            $_SESSION['reset_email'] = $user['email'];
            $_SESSION['reset_token'] = $reset_token;
            
            $success = "Password reset link has been sent to your email";
        } else {
            $error = "No account found with that email/username";
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="en" dir="ltr" class="landing-pages">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reset Password | Kivicare - Medical Clinic & Patient Management</title>
    <!-- Config Options -->
    <meta name="setting_options" content='{&quot;saveLocal&quot;:&quot;sessionStorage&quot;,&quot;storeKey&quot;:&quot;huisetting&quot;,&quot;setting&quot;:{&quot;app_name&quot;:{&quot;value&quot;:&quot;Kivicare&quot;},&quot;theme_scheme_direction&quot;:{&quot;value&quot;:&quot;ltr&quot;},&quot;theme_scheme&quot;:{&quot;value&quot;:&quot;light&quot;},&quot;theme_style_appearance&quot;:{&quot;value&quot;:[&quot;theme-default&quot;]},&quot;theme_color&quot;:{&quot;colors&quot;:{&quot;--{{prefix}}primary&quot;:&quot;#7093e5&quot;,&quot;--{{prefix}}secondary&quot;:&quot;#f68685&quot;},&quot;value&quot;:&quot;theme-color-default&quot;},&quot;header_navbar&quot;:{&quot;value&quot;:&quot;default&quot;},&quot;header_banner&quot;:{&quot;value&quot;:&quot;default&quot;},&quot;footer&quot;:{&quot;value&quot;:&quot;default&quot;},&quot;body_font_family&quot;:{&quot;value&quot;:null},&quot;heading_font_family&quot;:{&quot;value&quot;:null}}}'>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/core/libs.min.css" />
    <link rel="stylesheet" href="assets/vendor/flaticon/css/flaticon.css" />
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/kivicare.mine209.css?v=1.0.0" />
    <link rel="stylesheet" href="assets/css/custom.mine209.css?v=1.0.0" />
    
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@300;400;500;600;700&amp;family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;1,300;1,400;1,500&amp;display=swap" rel="stylesheet">
</head>
<body class="body-bg landing-pages">
    <span class="screen-darken"></span>
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body">
                <img src="assets/images/loader.gif" alt="loader" class="light-loader img-fluid" width="200">
            </div>
        </div>
    </div>
    <!-- loader END -->
    <main class="main-content">
        <div class="sign-in-page position-relative">
            <div class="container">
                <div class="row justify-content-center align-items-center height-self-center h-100">
                    <div class="col-lg-5 col-md-12 align-self-center">
                        <div class="sign-user_card position-relative bg-white mx-auto">
                            <div class="logo-img">
                                <a href="../index.php" class="navbar-brand d-flex align-items-center justify-content-center mb-5">
                                    <!-- Logo SVG (same as original) -->
                                    <div class="logo-main">
                                        <div class="logo-normal">
                                            <svg class="icon-30" width="30" height="31" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <!-- Your original SVG paths here -->
                                            </svg>
                                        </div>
                                    </div>
                                    <h1 class="logo-title ms-3 mb-0" data-setting="app_name">Kivicare</h1>
                                </a>
                            </div>
                            
                            <?php if (!empty($error)): ?>
                            <div class="alert alert-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($success)): ?>
                            <div class="alert alert-success mb-4"><?php echo htmlspecialchars($success); ?></div>
                            <?php endif; ?>
                            
                            <form method="post">
                                <p class="mb-5">Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.</p>
                                <div class="custom-form-field">
                                    <input type="text" name="user_login" placeholder="Enter Username or Email*" class="form-control mb-5" required>
                                </div>
                                <button type="submit" class="iq-button text-capitalize border-0">
                                    <span class="iq-btn-text-holder position-relative">reset password</span>
                                    <span class="iq-btn-icon-holder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 8 8" fill="none">
                                            <path d="M7.32046 4.70834H4.74952V7.25698C4.74952 7.66734 4.41395 8 4 8C3.58605 8 3.25048 7.66734 3.25048 7.25698V4.70834H0.679545C0.293423 4.6687 0 4.34614 0 3.96132C0 3.5765 0.293423 3.25394 0.679545 3.21431H3.24242V0.673653C3.28241 0.290878 3.60778 0 3.99597 0C4.38416 0 4.70954 0.290878 4.74952 0.673653V3.21431H7.32046C7.70658 3.25394 8 3.5765 8 3.96132C8 4.34614 7.70658 4.6687 7.32046 4.70834Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript Files -->
    <script src="assets/js/core/libs.min.js"></script>
    <script src="assets/js/plugins/slider-tabs.js"></script>
    <script src="assets/js/plugins/fslightbox.js" defer></script>
    <script src="assets/vendor/lodash/lodash.min.js"></script>
    <script src="assets/js/iqonic-script/utility.min.js"></script>
    <script src="assets/js/iqonic-script/setting.min.js"></script>
    <script src="assets/js/setting-init.js"></script>
    <script src="assets/js/core/external.min.js"></script>
    <script src="assets/js/kivicaree209.js?v=1.0.0" defer></script>
    <script src="assets/js/kivicare-advancee209.js?v=1.0.0" defer></script>
    <script src="assets/js/slider.js" defer></script>
    <script src="assets/js/scroll-text.js" defer></script>
</body>
</html>