<?php
// confirm.php
require_once __DIR__ . '/bootstrap.php';
use Model\Repository\UserRepository;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = 'Email Confirmation';
include_once 'templates/header.php';

try {
    // Check if token is provided
    if (!isset($_GET['token']) || empty($_GET['token'])) {
        throw new \Exception('Invalid confirmation link.');
    }

    $token = htmlspecialchars($_GET['token']);
    $userRepo = new UserRepository($entityManager);
    
    // Find user by confirmation token
    $user = $userRepo->findOneBy(['confirmationToken' => $token]);
    
    if (!$user) {
        throw new \Exception('Invalid confirmation token or account already confirmed.');
    }

    // Update user confirmation status
    $user->setIsConfirmed(true);
    $user->setConfirmationToken(null); // Clear the token
    $userRepo->save($user);

    $success_message = 'Your email has been confirmed successfully! You can now login.';

} catch (\Exception $e) {
    $error_message = $e->getMessage();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                        <a href="login.php" class="btn btn-primary">Proceed to Login</a>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                        <a href="register.php" class="btn btn-primary">Back to Registration</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'templates/footer.php'; ?>