<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer(true);

    try {
        // --- SITEGROUND SMTP CONFIGURATION ---
        $mail->isSMTP();
        $mail->Host       = 'mail.luna-diving-thailand.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@luna-diving-thailand.com';  
        $mail->Password   = 'Luna$webmail26';          // Make sure your real password is here!
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;     
        $mail->Port       = 465;                             

        // --- SENDER & RECIPIENT ---
        $mail->setFrom('info@luna-diving-thailand.com', 'Luna Diving Website');
        $mail->addAddress('info@luna-diving-thailand.com'); 
        
        if (!empty($_POST['email'])) {
            $mail->addReplyTo($_POST['email'], $_POST['name'] ?? '');
        }

        // --- EMAIL CONTENT ---
        $name    = htmlspecialchars($_POST['name'] ?? 'Not specified');
        $email   = htmlspecialchars($_POST['email'] ?? 'Not specified');
        $service = htmlspecialchars($_POST['service'] ?? 'Not specified');
        $message = htmlspecialchars($_POST['message'] ?? '');

        $mail->isHTML(true);
        $mail->Subject = "New website inquiry - " . $service;
        $mail->Body    = "
            <h2>New Contact Form Message</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Service:</strong> {$service}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        ";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Email sent successfully!']);
    } catch (Exception $e) {
        http_response_code(500);
        // Safely encode the actual error message into JSON so JavaScript can read it without crashing
        echo json_encode(['status' => 'error', 'message' => 'Mail Error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}