<?php
// reset-password.php
require_once __DIR__ . '/bootstrap.php';
use Model\Repository\UserRepository;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = 'Reset Password';
include_once 'templates/header.php';

// Verify token
$token = $_GET['token'] ?? '';
$userRepo = new UserRepository($entityManager);
$user = $userRepo->findOneBy(['resetToken' => $token]);

$tokenValid = false;
if ($user && !$user->isResetTokenExpired()) {
    $tokenValid = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValid) {
    try {
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate passwords
        if (strlen($password) < 8) {
            throw new \Exception('Password must be at least 8 characters long.');
        }

        if ($password !== $confirmPassword) {
            throw new \Exception('Passwords do not match.');
        }

        // Update password
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $user->setResetToken(null);
        $user->setResetTokenExpires(null);
        $userRepo->save($user);

        $success_message = 'Your password has been reset successfully. You can now login.';

    } catch (\Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Reset Password</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                            <div class="text-center mt-3">
                                <a href="login.php" class="btn btn-primary">Proceed to Login</a>
                            </div>
                        </div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($tokenValid && !isset($success_message)): ?>
                        <form method="POST" action="" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required minlength="8">
                                <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" 
                                       name="confirm_password" required>
                                <div class="invalid-feedback">Please confirm your password.</div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </form>
                    <?php elseif (!isset($success_message)): ?>
                        <div class="alert alert-danger">
                            Invalid or expired reset link. Please request a new password reset.
                        </div>
                        <div class="text-center mt-3">
                            <a href="forgot-password.php" class="btn btn-primary">Request New Reset Link</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'templates/footer.php'; ?>