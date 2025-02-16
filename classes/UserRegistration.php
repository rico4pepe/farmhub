<?php

namespace ClassFolder; // Define your namespace

use Model\User; // Import the User model
use Model\Repository\UserRepository; // Import the custom UserRepository if needed
use Doctrine\ORM\EntityManagerInterface; // Doctrine entity manager
use Respect\Validation\Validator as v; // Import Respect\Validation\Validator
use Exception;
use PHPMailer\PHPMailer\PHPMailer; // Import PHPMailer for sending emails

class UserRegistration
{
    private EntityManagerInterface $entityManager;
    private string $siteUrl;

    public function __construct(EntityManagerInterface $entityManager, string $siteUrl)
    {
        $this->entityManager = $entityManager;
        $this->siteUrl = $siteUrl; // URL of your site (for building the confirmation link)
    }

    public function registerUser(array $data): string
    {
        // Validate the form data using Respect/Validation
        $validationResults = $this->validate($data);
        
        if ($validationResults !== true) {
            return implode('<br>', $validationResults); // Return validation errors if any
        }

        // Check if the username or email already exists in the database
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);
        if ($existingUser) {
            return "Username already exists.";
        }

        $existingEmail = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingEmail) {
            return "Email already exists.";
        }

        // Create a new user
        $user = new User(
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT), // Hash password
            $data['email'],
            $data['first_name'],
            $data['last_name']
        );

        // Generate a confirmation token
        $confirmationToken = bin2hex(random_bytes(16)); // Generates a random token (you can also use UUID)
        $user->setConfirmationToken($confirmationToken);

        try {
            // Save the user to the database
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Send confirmation email
            $this->sendConfirmationEmail($user->getEmail(), $confirmationToken);

            return "User successfully registered! Please check your email to confirm your registration.";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    private function validate(array $data)
    {
        $errors = [];

        // Validate username
        try {
            v::stringType()->length(3, 255)->alnum()->validate($data['username']);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors[] = "Username must be alphanumeric and between 3 and 255 characters.";
        }

        // Validate email
        try {
            v::email()->validate($data['email']);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors[] = "Invalid email format.";
        }

        // Validate password (minimum 6 characters)
        try {
            v::stringType()->length(6, null)->validate($data['password']);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors[] = "Password must be at least 6 characters.";
        }

        // Validate first name
        try {
            v::stringType()->length(2, 255)->validate($data['first_name']);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors[] = "First name must be between 2 and 255 characters.";
        }

        // Validate last name
        try {
            v::stringType()->length(2, 255)->validate($data['last_name']);
        } catch (\Respect\Validation\Exceptions\ValidationException $e) {
            $errors[] = "Last name must be between 2 and 255 characters.";
        }

        return empty($errors) ? true : $errors;
    }

    private function sendConfirmationEmail(string $email, string $token)
    {
        // Set up PHPMailer to send the email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; // Your email address
            $mail->Password = 'your-email-password'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('no-reply@example.com', 'FarmHub');
            $mail->addAddress($email); // Recipient email

            // Content
            $confirmationLink = $this->siteUrl . "/confirm-email?token=" . $token; // Confirmation link

            $mail->isHTML(true);
            $mail->Subject = 'Email Confirmation for FarmHub Registration';
            $mail->Body    = 'Please click on the following link to confirm your registration: <a href="' . $confirmationLink . '">' . $confirmationLink . '</a>';

            $mail->send();
        } catch (Exception $e) {
            // Handle email sending failure
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
