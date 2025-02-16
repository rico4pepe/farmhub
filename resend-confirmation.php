<?php
// resend-confirmation.php
require_once __DIR__ . '/bootstrap.php';
use Model\Repository\UserRepository;
use Services\EmailServices;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = 'Resend Confirmation Email';
include_once 'templates/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format.');
        }

        $userRepo = new UserRepository($entityManager);
        $user = $userRepo->findByEmail($email);

        if (!$user) {
            throw new \Exception('No account found with this email address.');
        }

        if ($user->getIsConfirmed()) {
            throw new \Exception('This email is already confirmed.');
        }

        // Generate new confirmation token
        $newToken = bin2hex(random_bytes(32));
        $user->setConfirmationToken($newToken);
        $userRepo->save($user);

        // Send new confirmation email
        $emailService = new EmailServices();
        if ($emailService->sendConfirmationEmail($user->getEmail(), $user->getUsername(), $newToken)) {
            $success_message = 'A new confirmation email has been sent. Please check your inbox.';
        } else {
            throw new \Exception('Failed to send confirmation email. Please try again.');
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
                    <h3 class="card-title text-center mb-4">Resend Confirmation Email</h3>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Resend Confirmation Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'templates/footer.php'; ?>