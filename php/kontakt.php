<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

include '../layouts/header.php'; // Das Header-Layout einbinden

echo '<main id="content"><div class="content"><div class="blogbeitrag"><section>';

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
                echo '<p>Danke für deine Nachricht. Ich werden mich bald bei dir melden.</p>';
            } catch (Exception $e) {
                echo "<p>Fehler: Die Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}</p>";
            }
        } else {
            echo "<p>Ungültige E-Mail-Adresse.</p>";
        }
    } else {
        echo "<p>Bitte alle Felder ausfüllen.</p>";
    }
} else {
    echo "<p>Ungültige Anforderung.</p>";
}

echo '</section></div></div></main>'; // Das Ende des Content-Blocks

include '../layouts/footer.php'; // Das Footer-Layout einbinden
?>
