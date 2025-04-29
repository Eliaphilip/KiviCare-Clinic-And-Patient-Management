<?php
/**
 * Password reset email template
 * Variables available:
 * - $reset_link: The password reset URL
 * - $user_email: User's email address
 * - $expiry: When the link expires
 */
?>
<h1>Password Reset Request</h1>
<p>You requested to reset your password for Kivicare. Click the link below to set a new password:</p>
<p><a href="<?php echo $reset_link; ?>">Reset Password</a></p>
<p>This link will expire on <?php echo $expiry; ?></p>
<p>If you didn't request this, please ignore this email.</p>