<?php
// Start session and check if user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Database configuration
$db_host = 'localhost';
$db_name = 'kivicare_db';
$db_user = 'root';
$db_pass = '';

// Initialize variables
$error = '';
$success = '';

// Process registration form if submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Connect to database
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);

        // Get form data
        $email = trim($_POST['user-name']);
        $password = bin2hex(random_bytes(8)); // Generate random password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        // Generate verification token
        $token = bin2hex(random_bytes(32));
        $token_expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered";
        } else {
            // Insert new user
            $stmt = $pdo->prepare("INSERT INTO users (email, password, verification_token, token_expiry, created_at) 
                                  VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$email, $hashed_password, $token, $token_expiry]);
            
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
?>
<!doctype html>
<html lang="en" dir="ltr" class="landing-pages">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register | Kivicare - Medical Clinic & Patient Management</title>
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
        <div class="position-relative">
            <!--Nav Start-->
            <!--bread-crumb-->
        </div>
        
        <div class="sign-in-page position-relative">
            <div class="container">
                <div class="row justify-content-center align-items-center height-self-center h-100">
                    <div class="col-lg-5 col-md-12 align-self-center">
                        <div class="sign-user_card position-relative bg-white mx-auto">
                            <div class="logo-img">
                                <a href="index.html" class="navbar-brand d-flex align-items-center justify-content-center mb-5">
                                    <!--Logo start-->
                                    <div class="logo-main">
                                        <div class="logo-normal">
                                            <svg class="icon-30" width="30" height="31" viewBox="0 0 30 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path class="text-secondary" d="M21.7 6.7499C21.7 5.0798 21.7 3.4097 21.7 1.7396C21.7 1.0769 21.2509 0.5 20.5882 0.5H9.78822C9.12552 0.5 8.50002 1.0769 8.50002 1.7396V8.9H1.63453C0.971826 8.9 0.400024 9.4427 0.400024 10.1054V20.9054C0.400024 21.5681 0.971826 22.1 1.63453 22.1C3.42313 22.0832 5.21172 22.0667 7.00002 22.0499C6.93732 21.1829 6.62923 15.6008 10.9627 11.1077C15.274 6.6374 20.8264 6.722 21.7 6.7499Z" fill="currentColor"></path>
                                                    <path class="text-primary" d="M28.5505 8.8999H21.7999H20.8003C14.1731 8.8999 8.80005 14.4217 8.80005 21.0493V29.4493C8.80005 30.112 9.33765 30.4999 10.0007 30.4999H20.6003C21.263 30.4999 21.7003 30.112 21.7003 29.4493V24.4999H28.5508C29.2135 24.4999 29.8003 24.112 29.8003 23.4493V10.2493C29.8 9.5863 29.2132 8.8999 28.5505 8.8999Z" fill="currentColor"></path>
                                                    <path class="text-primary-dark" fill-rule="evenodd" clip-rule="evenodd" d="M8.83665 22.0491C8.82797 22.0486 8.81968 22.0482 8.81177 22.0478C8.81177 22.0478 8.81933 22.0361 8.83412 22.0136C8.7745 21.1484 8.5664 16.0579 12.591 12.2322C16.0804 8.91496 20.3292 8.85159 21.5037 8.8915C21.5613 8.8872 21.6181 8.8851 21.6743 8.88526C21.6746 8.88961 21.6749 8.89413 21.6753 8.89882C21.6839 8.89927 21.6922 8.8997 21.7002 8.90012C21.7002 8.90012 21.6926 8.91175 21.6778 8.9343C21.7374 9.79948 21.9455 14.89 17.921 18.7157C14.4317 22.0328 10.183 22.0963 9.00833 22.0564C8.95072 22.0607 8.89382 22.0628 8.83766 22.0626C8.83733 22.0583 8.83699 22.0538 8.83665 22.0491Z" fill="currentColor"></path>
                                                </g>
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