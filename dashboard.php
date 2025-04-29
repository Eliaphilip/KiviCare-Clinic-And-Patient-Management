<?php
require_once 'config.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user data
try {
    $stmt = $pdo->prepare("SELECT email, created_at, last_login FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Dashboard | Kivicare</title>
    <!-- [Your existing head content] -->
    <style>
        .user-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .welcome-text {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- [Your loader section] -->
    <main class="main-content">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card user-card">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h2>Welcome to Kivicare</h2>
                                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                            </div>
                            
                            <div class="welcome-text mb-4">
                                <p>Hello, <strong><?php echo htmlspecialchars($user['email']); ?></strong>!</p>
                                <p>You joined us on <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                                <?php if ($user['last_login']): ?>
                                <p>Last login: <?php echo date('F j, Y g:i a', strtotime($user['last_login'])); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Profile Settings</h5>
                                            <p class="card-text">Update your personal information and preferences.</p>
                                            <a href="profile.php" class="btn btn-primary">Go to Profile</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Change Password</h5>
                                            <p class="card-text">Update your account password for better security.</p>
                                            <a href="change-password.php" class="btn btn-primary">Change Password</a>
                                        </div>
                                    </div>
                                </div>
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