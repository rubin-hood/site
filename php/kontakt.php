<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// LADE DIE AUTOLOAD-DATEI FÜR DIE PHPMailer-KLASSE
require __DIR__ . '/../vendor/autoload.php';

// HEADER EINBINDEN, DAMIT DAS DESIGN DER SEITE BEIBEHALTEN WIRD
include '../layouts/header.php';

// HTML-STRUKTUR DER SEITE ERSTELLEN, DAMIT DIE ERFOLGSMELDUNG IM GLEICHEN LAYOUT WIE DAS FORMULAR ANGEZEIGT WIRD
echo '<main id="content">';
echo '<div class="content">';
echo '<div class="blogbeitrag">';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // BENUTZEREINGABEN VERARBEITEN UND SICHERSTELLEN, DASS KEINE UNERWÜNSCHTEN ZEICHEN ENTHALTEN SIND
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mail = new PHPMailer(true);
            try {
                // SERVER-EINSTELLUNGEN FÜR DEN E-MAIL-VERSAND KONFIGURIEREN
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // SMTP-SERVER
                $mail->SMTPAuth = true;
                $mail->Username = 'anjdele@gmail.com';  // SMTP-BENUTZERNAME
                $mail->Password = 'gbsbvmnodymtnagt';  // SMTP-PASSWORT (APP-SPEZIFISCHES PASSWORT)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // EMPFÄNGER DEFINIEREN
                $mail->setFrom('anjdele@gmail.com', 'Webseiten Kontaktformular');  // ABSENDERADRESSE
                $mail->addAddress('anjdele@gmail.com');  // EMPFÄNGERADRESSE

                // E-MAIL-INHALT DEFINIEREN
                $mail->isHTML(false);
                $mail->Subject = 'Neue Nachricht von deiner Webseite';
                $mail->Body = "Name: $name\nE-Mail: $email\nNachricht:\n$message";

                // E-MAIL SENDEN UND ERFOLGSMELDUNG AUSGEBEN
                $mail->send();
                echo '<p>Danke für deine Nachricht. Wir werden uns bald bei dir melden.</p>';
            } catch (Exception $e) {
                // FEHLERMELDUNG AUSGEBEN, FALLS DIE E-MAIL NICHT GESENDET WERDEN KONNTE
                echo "<p>Fehler: Die Nachricht konnte nicht gesendet werden. Fehler: {$mail->ErrorInfo}</p>";
            }
        } else {
            // FEHLERMELDUNG FÜR UNGÜLTIGE E-MAIL-ADRESSE
            echo "<p>Ungültige E-Mail-Adresse.</p>";
        }
    } else {
        // FEHLERMELDUNG, WENN FELDER LEER SIND
        echo "<p>Bitte alle Felder ausfüllen.</p>";
    }
} else {
    // FEHLERMELDUNG FÜR UNGÜLTIGE ANFORDERUNG
    echo "<p>Ungültige Anforderung.</p>";
}

// HTML-STRUKTUR SCHLIEßEN
echo '</div>';
echo '</div>';
echo '</main>';

// FOOTER EINBINDEN, DAMIT DAS DESIGN KONSISTENT BLEIBT
include '../layouts/footer.php';
?>