<?php 
// Include the bootstrap file to load configurations, database, and environment variables
require_once __DIR__ . '/bootstrap.php'; 
use Model\User;
use Model\Repository\UserRepository;



 // Generate CSRF token if not set
 if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$title = 'Welcome to FarmConnect Hub';

// Include the header template and pass the title
include_once 'templates/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

try{

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new \Exception('Invalid CSRF token.');
    }
    // Get form data // Sanitize and validate input
        $username = htmlspecialchars(trim($_POST['username']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = htmlspecialchars(trim($_POST['password']));
        $firstName = htmlspecialchars(trim($_POST['first_name']));
        $lastName = htmlspecialchars(trim($_POST['last_name']));


         // Validate email format
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format.');
        }

 
     // Check if username or email exists
    // Check if username or email exists
    $userRepo = new UserRepository($entityManager);
    if ($userRepo->findByEmail($email)) {
        throw new \Exception('Email already in use.');
        exit;
    }
    if ($userRepo->findByUsername($username)) {
        throw new \Exception('Username already in use.');
        exit;
    }

       // Hash password
       $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

       // Generate confirmation token
       $confirmationToken = bin2hex(random_bytes(32));

    

     // Create new user using constructor
     $user = new User(
        username: $username,
        password: $hashedPassword,
        email: $email,
        firstName: $firstName,
        lastName: $lastName
    );
 
    // Set additional properties
    $user->setIsConfirmed(false);
    $user->setConfirmationToken($confirmationToken);

    

    // Save the user
    $userRepo->save($user);

      // TODO: Send confirmation email with token
        // For now, we'll just store it in session for demo purposes
        $_SESSION['success_message'] = 'Registration successful! Please check your email to confirm your account.';
        header('Location: login.php');
        exit;

    

} catch (\Exception $e) {
    $error_message = $e->getMessage();
}
}

    
    
    

?>

    <!-- Login Container -->
    <div class="d-flex flex-column flex-center min-h-100px">
        <div class="login-form w-400px">
            <!-- Logo -->
            <div class="login-logo text-center mb-4">
                <img src="assets/media/logos/farmhub-logo.png" alt="FarmHub Logo">
            </div>

            <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- Username Field -->
    <div class="mb-4">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Enter your username" required>
    </div>

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

    <!-- First Name Field -->
    <div class="mb-4">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" placeholder="Enter your first name" required>
    </div>

    <!-- Last Name Field -->
    <div class="mb-4">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" placeholder="Enter your last name" required>
    </div>

    <!-- Registration Button -->
    <button type="submit" class="btn btn-lg btn-block login-btn">Register</button>
</form>


          
         
        </div>
    </div>
<?php 
include_once 'templates/footer.php';
?>


