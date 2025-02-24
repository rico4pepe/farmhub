<?php 
require_once __DIR__ . '/bootstrap.php'; 
use Model\User;
use Model\Repository\UserRepository;


// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$title = 'Welcome to FarmConnect Hub';

// Include the header template and pass the title
include_once 'templates/header.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error_message = 'Email and Password are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } else {
        // Create an instance of UserRepository
        $userRepo = new UserRepository($entityManager);
        
        // Find the user by email
        $user = $userRepo->findByEmail($email);
        
        if (!$user) {
            $error_message = 'User not found.';
        } else {
            // Verify the password
            if (password_verify($password, $user->getPassword())) {
                // Password is correct; set session variables and redirect to account page
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                header('Location: index.php');
                exit;
            } else {
                $error_message = 'Incorrect password.';
            }
        }
    }
}
?>

<!-- Display error alert if exists -->
<?php if (!empty($error_message)): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>

    <!-- Login Container -->
    <div class="d-flex flex-column flex-center min-h-100px">
        <div class="login-form w-400px">
            <!-- Logo -->
            <div class="login-logo text-center mb-4">
                <img src="assets/media/logos/farmhub-logo.png" alt="FarmHub Logo">
            </div>

            <!-- Login Form -->
            <form method="POST" action="" >
                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="d-flex justify-content-between mb-4">
                    <label class="form-check-label" for="remember_me">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me"> Remember me
                    </label>
                    <a href="forgot-password.php" class="text-primary">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-lg btn-block login-btn">Login</button>
				</form>

            <!-- Register Link -->
            <div class="text-center mt-4">
                <p class="text-muted">Don't have an account? <a href="register.php" class="text-primary">Sign up</a></p>
            </div>
        </div>
    </div>
<?php 
include_once 'templates/footer.php';
?>
