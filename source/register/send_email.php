<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer library files
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // SMTP server configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'n0tehubbb@gmail.com'; // SMTP username
    $mail->Password = 'dzdj drcv unur obnb'; // Use environment variable for password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption type
    $mail->Port = 587; // SMTP port

    // Set sender and recipient
    $mail->setFrom('n0tehubbb@gmail.com', 'N0teHub Mailer'); // Sender's email and name
    $mail->addAddress('hoainamxmen@gmail.com', 'Recipient Name'); // Replace with recipient's email and name

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<p>This is a <strong>test email</strong> sent using PHPMailer with your SMTP settings!</p>';
    $mail->AltBody = 'This is a test email sent using PHPMailer with your SMTP settings!';

    // Send the email
    $mail->send();
    echo 'Message has been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Error: " . $mail->ErrorInfo;
    error_log("Detailed Error: " . $mail->ErrorInfo);
}
?>