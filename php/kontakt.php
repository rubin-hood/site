<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt - Rubin-Hood</title>
    <link rel="stylesheet" href="/styles.css"> <!-- Verlinke zu deiner CSS-Datei -->
</head>
<body>
    <header>
        <img src="/logo.png" alt="Rubin-Hood Logo" class="logo">
        <nav>
            <ul>
                <li><a href="/index.html">Home</a></li>
                <li><a href="/about.html">Über mich</a></li>
                <li><a href="/projects.html">Meine Projekte</a></li>
                <li><a href="/contact.html">Kontakt</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="contact">
            <h2>Kontaktformular</h2>
            <div class="contact-box">
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
                                echo '<p>Danke für deine Nachricht. Wir werden uns bald bei dir melden.</p>';
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
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Rubin-Hood</p>
        <div class="social-icons">
            <a href="#"><img src="/github-icon.png" alt="GitHub"></a>
            <a href="#"><img src="/twitter-icon.png" alt="Twitter"></a>
            <a href="#"><img src="/youtube-icon.png" alt="YouTube"></a>
        </div>
    </footer>
</body>
</html>