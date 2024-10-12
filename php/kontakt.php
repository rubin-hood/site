<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail = new PHPMailer(true);
            try {
                // Servereinstellungen
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // SMTP-Server
                $mail->SMTPAuth = true;
                $mail->Username = 'anjdele@gmail.com';  // SMTP-Benutzername
                $mail->Password = 'gbsbvmnodymtnagt';  // SMTP-Passwort (App-spezifisches Passwort)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Empfänger
                $mail->setFrom('anjdele@gmail.com', 'Webseiten Kontaktformular');  // Absenderadresse
                $mail->addAddress('anjdele@gmail.com');  // Deine E-Mail-Adresse

                // Inhalt
                $mail->isHTML(false);
                $mail->Subject = 'Neue Nachricht von deiner Webseite';
                $mail->Body = "Name: $name\nE-Mail: $email\nNachricht:\n$message";

                $mail->send();
                echo 'Danke für deine Nachricht. Wir werden uns bald bei dir melden.';
            } catch (Exception $e) {
                echo "Fehler: Die Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}";
            }
        } else {
            echo "Ungültige E-Mail-Adresse.";
        }
    } else {
        echo "Bitte alle Felder ausfüllen.";
    }
} else {
    echo "Ungültige Anforderung.";
}
?>