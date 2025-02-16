<?php
// src/Services/EmailService.php
namespace Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailServices {
    private PHPMailer $mailer;
    private string $fromEmail;
    private string $fromName;
    private string $baseUrl;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->fromEmail = 'noreply@farmhub.com';
        $this->fromName = 'FarmHub';
        $this->baseUrl = 'http://yourdomain.com'; // Change this to your domain
        
        // Configure PHPMailer
        $this->setupMailer();
    }

    private function setupMailer(): void {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = 'smtp.gmail.com'; // Change to your SMTP host
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = 'your-email@gmail.com'; // Your SMTP username
            $this->mailer->Password = 'your-app-password'; // Your SMTP password
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = 587;
            $this->mailer->setFrom($this->fromEmail, $this->fromName);
            $this->mailer->isHTML(true);
        } catch (Exception $e) {
            throw new \Exception('Mailer configuration failed: ' . $e->getMessage());
        }
    }

    public function sendConfirmationEmail(string $toEmail, string $username, string $token): bool {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $username);
            $this->mailer->Subject = 'Confirm Your FarmHub Account';
            
            $confirmationLink = "{$this->baseUrl}/confirm.php?token=" . urlencode($token);
            $this->mailer->Body = $this->getConfirmationEmailTemplate($username, $confirmationLink);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendPasswordResetEmail(string $toEmail, string $username, string $token): bool {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $username);
            $this->mailer->Subject = 'Reset Your FarmHub Password';
            
            $resetLink = "{$this->baseUrl}/reset-password.php?token=" . urlencode($token);
            $this->mailer->Body = $this->getPasswordResetEmailTemplate($username, $resetLink);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $e->getMessage());
            return false;
        }
    }

    // Email templates...
    private function getConfirmationEmailTemplate(string $username, string $confirmationLink): string {
        // Your existing email template HTML
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                .email-container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <h2>Welcome to FarmHub!</h2>
                <p>Hello {$username},</p>
                <p>Thank you for registering with FarmHub. To complete your registration, please click the button below:</p>
                <p><a href="{$confirmationLink}" class="button">Confirm Your Email</a></p>
                <p>Or copy and paste this link in your browser:</p>
                <p>{$confirmationLink}</p>
                <p>If you didn't create this account, you can safely ignore this email.</p>
                <p>Best regards,<br>The FarmHub Team</p>
            </div>
        </body>
        </html>
        HTML;
    }

    private function getPasswordResetEmailTemplate(string $username, string $resetLink): string {
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                .email-container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <h2>Password Reset Request</h2>
                <p>Hello {$username},</p>
                <p>We received a request to reset your FarmHub password. Click the button below to create a new password:</p>
                <p><a href="{$resetLink}" class="button">Reset Password</a></p>
                <p>Or copy and paste this link in your browser:</p>
                <p>{$resetLink}</p>
                <p>This link will expire in 1 hour. If you didn't request a password reset, please ignore this email.</p>
                <p>Best regards,<br>The FarmHub Team</p>
            </div>
        </body>
        </html>
        HTML;
    }
}