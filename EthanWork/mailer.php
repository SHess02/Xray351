<?php
// Ethan Belote
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../EthanWork/config_smtp.php';

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER; 
        $mail->Password = SMTP_PASS; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(SMTP_USER, 'No-Reply');
        $mail->addAddress($email);
        $mail->addReplyTo(SMTP_USER, 'Support');

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email - AlumniConnect';

        $base_url = "http://" . $_SERVER['HTTP_HOST']; 
        $verificationLink = "localhost/Xray351/EthanWork/account_verified.php?token=$token";

        $mail->Body = "
            <p>Thank you for signing up!</p>
            <p>Please click the link below to verify your email:</p>
            <p><a href='$verificationLink'>$verificationLink</a></p>
            <p>If you did not sign up, you can ignore this email.</p>
            <p>Best regards,</p>
            <p>AlumniConnect Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending error: " . $mail->ErrorInfo);
        return false;
    }
}

function sendPasswordResetEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER; 
        $mail->Password = SMTP_PASS; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(SMTP_USER, 'No-Reply');
        $mail->addAddress($email);
        $mail->addReplyTo(SMTP_USER, 'Support');

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request - AlumniConnect';

        $base_url = "http://" . $_SERVER['HTTP_HOST'];
        $resetLink = "$base_url/Xray351/SH_folder/reset_password.php?token=$token";

        $mail->Body = "
            <p>You have requested a password reset.</p>
            <p>Please click the link below to reset your password. This link will expire in 1 hour:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>If you did not request this, you can ignore this email.</p>
            <p>Best regards,</p>
            <p>AlumniConnect Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email sending error: " . $mail->ErrorInfo);
        return false;
    }
}
?>