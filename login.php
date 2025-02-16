<?php 
$title = 'Welcome to FarmConnect Hub';

// Include the header template and pass the title
include_once 'templates/header.php';
?>

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
