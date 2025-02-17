<?php
// forgot-password.php
require_once __DIR__ . '/bootstrap.php';
use Model\Repository\UserRepository;
use Services\EmailServices;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = 'Forgot Password';
include_once 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format.');
        }

        $userRepo = new UserRepository($entityManager);
        $user = $userRepo->findByEmail($email);

        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = new \DateTime('+1 hour');
            
            $user->setResetToken($token);
            $user->setResetTokenExpires($expires);
            $userRepo->save($user);

            // Send reset email
            $emailService = new EmailServices();
            if ($emailService->sendPasswordResetEmail($user->getEmail(), $user->getUsername(), $token)) {
                $success_message = 'Password reset instructions have been sent to your email.';
            } else {
                throw new \Exception('Failed to send reset email. Please try again.');
            }
        } else {
            // For security, show the same message even if email doesn't exist
            $success_message = 'If an account exists with this email, password reset instructions have been sent.';
        }

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
                    <h3 class="card-title text-center mb-4">Forgot Password</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please provide a valid email.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="login.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'templates/footer.php'; ?>