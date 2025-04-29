<?php
session_start();
if (!isset($_SESSION['temp_email'])) {
    header("Location: register.php");
    exit();
}

$email = $_SESSION['temp_email'];
$password = $_SESSION['temp_password'];
?>
<!doctype html>
<html lang="en">
<head>
    <title>Registration Successful | Kivicare</title>
    <!-- [Your existing head content] -->
</head>
<body>
    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center py-5">
                    <div class="card shadow">
                        <div class="card-body p-5">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16.78 9.7L11.11 15.37C10.97 15.51 10.78 15.59 10.58 15.59C10.38 15.59 10.19 15.51 10.05 15.37L7.22 12.54C6.93 12.25 6.93 11.77 7.22 11.48C7.51 11.19 7.99 11.19 8.28 11.48L10.58 13.78L15.72 8.64C16.01 8.35 16.49 8.35 16.78 8.64C17.07 8.93 17.07 9.4 16.78 9.7Z" fill="#4CAF50"/>
                            </svg>
                            <h2 class="mt-3">Registration Successful!</h2>
                            <p class="lead">Your account has been created successfully.</p>
                            
                            <div class="alert alert-info mt-4">
                                <h5>Your temporary credentials:</h5>
                                <p>Email: <?php echo htmlspecialchars($email); ?></p>
                                <p>Password: <?php echo htmlspecialchars($password); ?></p>
                                <p class="text-muted">Please change your password after logging in.</p>
                            </div>
                            
                            <a href="login.php" class="btn btn-primary mt-3">Proceed to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<?php
// Clear temporary session data
unset($_SESSION['temp_email']);
unset($_SESSION['temp_password']);
unset($_SESSION['temp_token']);
?>