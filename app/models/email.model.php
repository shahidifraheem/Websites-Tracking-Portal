<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

/**
 * Email Sender
 * 
 */
class Email
{
    private $error = "";

    public function email_sender()
    {
        // Retrieve form data
        $to = EMAIL;
        $subject = "Contact Us - " . WEBSITE_TITLE;
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error .= 'Please enter a valid email address.<br>';
        }

        if (empty($message)) {
            $this->error .= "Please enter valid " . title('message') . " <br>";
        }
        if ($this->error == "") {
            $to = EMAIL;
            $subject = $name . " Contact Us - " . WEBSITE_TITLE;
            $message = $message;
            $headers = "From: " . $email . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";

            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            // Send the email
            $mailSent = mail($to, $subject, $message, $headers);

            if ($mailSent) {
                $_SESSION["success_email"] = "Email sent successfully.";
                header("Location: " . ROOT . "contact");
                die;
            } else {
                $_SESSION["error"] = "Email sent successfully.";
                header("Location: " . ROOT . "contact");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
        header("Location: " . ROOT . "contact");
        die;
    }

    public function newsletter_sender()
    {
        // Retrieve form data
        $to = EMAIL;
        $subject = "Newsletter - " . WEBSITE_TITLE;
        $email = trim($_POST['email']);
        $message = "A new user subscribed to our newsletter: " . $email;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error .= 'Please enter a valid email address.<br>';
        }

        if (empty($message)) {
            $this->error .= "Please enter valid " . title('message') . " <br>";
        }

        if ($this->error == "") {
            $to = EMAIL;
            $subject = "Contact Us - " . WEBSITE_TITLE;
            $message = $message;
            $headers = "From: " . $email . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";

            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

            // Send the email
            $mailSent = mail($to, $subject, $message, $headers);

            if ($mailSent) {
                $_SESSION["success_email"] = "You are subscribed Successfully.";
                header("Location: " . ROOT . "contact");
                die;
            } else {
                $_SESSION["error"] = "Failed to subscribed.";
                header("Location: " . ROOT . "contact");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
        header("Location: " . ROOT . "contact");
        die;
    }
}
