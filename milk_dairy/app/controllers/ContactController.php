<?php
class ContactController extends Controller
{
    // Accept POST AJAX request and send contact email (or fallback to file)
    public function send()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$name || !$email || !$message) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
            return;
        }

        $to = 'support@maulivision.in';
        $subject = "Website Contact: " . substr($name, 0, 100);
        $body = "Name: " . htmlspecialchars($name) . "\n";
        $body .= "Email: " . htmlspecialchars($email) . "\n\n";
        $body .= "Message:\n" . htmlspecialchars($message) . "\n";

        $headers = "From: " . $email . "\r\n" .
                   "Reply-To: " . $email . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        $sent = false;
        // Try native mail() if configured
        if (function_exists('mail')) {
            $sent = @mail($to, $subject, $body, $headers);
        }

        // Fallback: write to a local log file so messages aren't lost when mail is not configured
        if (!$sent) {
            $logDir = __DIR__ . '/../../storage';
            if (!is_dir($logDir)) mkdir($logDir, 0755, true);
            $logFile = $logDir . '/contact_messages.log';
            $entry = "---\n" . date('Y-m-d H:i:s') . "\n" . $body . "\n";
            file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
            // consider this success (we stored the message)
            $sent = true;
        }

        if ($sent) {
            echo json_encode(['success' => true, 'message' => 'Thanks — we will contact you shortly.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again later.']);
        }
    }
}
