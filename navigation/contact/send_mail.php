<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formulardaten holen
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // E-Mail-Inhalte
    $to = "upluts@gmail.com";  // Deine E-Mail-Adresse
    $subject = "Neue Kontaktanfrage von $name";
    $body = "Name: $name\nE-Mail: $email\n\nNachricht:\n$message";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // E-Mail senden
    if (mail($to, $subject, $body, $headers)) {
        echo "Vielen Dank für deine Nachricht, $name. Wir werden uns bald bei dir melden!";
    } else {
        echo "Fehler: Die Nachricht konnte nicht gesendet werden.";
    }
}
?>
