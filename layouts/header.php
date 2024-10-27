
<!------------------------------------------ Header ------------------------------------------>
<!------------------------------------------ Dokumentstruktur ------------------------------------------>
<!DOCTYPE html> <!-- Deklariert den Dokumenttyp als HTML5 -->
<html lang="de"> <!-- Beginn des HTML-Dokuments und legt die Sprache auf Deutsch fest -->
<head> <!-- Enthält Metainformationen über das Dokument -->
    <meta charset="UTF-8"> <!-- Legt die Zeichenkodierung des Dokuments auf UTF-8 fest -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Setzt das Viewport-Tag für responsives Design, damit die Seite auf mobilen Geräten richtig angezeigt wird -->
    <title>Rubin-Hood - IT Knowledge Made Understandable for Everyone</title> <!-- Definiert den Titel der Webseite, der im Browser-Tab angezeigt wird -->
    <link rel="stylesheet" href="../resources/styles_header.css">
    <link rel="stylesheet" href="../resources/styles_contact.css">
    <link rel="stylesheet" href="../resources/styles_inhalt.css">

    <!-- <link rel="preload" href="../resources/styles.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->
    <!-- <link rel="preload" href="../resources/styles_contact.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->
    <!-- <link rel="preload" href="../resources/styles_inhalt.css" as="style" onload="this.onload=null;this.rel='stylesheet'"> -->
    
    <!-- <link rel="stylesheet" href="../resources/styles.css"> Verknüpft das externe Stylesheet (CSS-Datei) zur Gestaltung der Seite -->
    <script defer src="../resources/script_header.js"></script> <!-- Lädt das externe JavaScript mit der Datei "script.js" und führt es aus, nachdem das HTML-Dokument vollständig geladen wurde -->
    <script defer src="../resources/script_inhalt.js"></script> <!-- Lädt das externe JavaScript mit der Datei "script.js" und führt es aus, nachdem das HTML-Dokument vollständig geladen wurde -->
    <script defer src="../resources/notion-renderer.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <!-- Verlinkt die Font Awesome Bibliothek für die Verwendung von Icons -->
</head>


<header> <!-- Definiert den Kopfbereich der Webseite -->
    <!-- <a href="#" class="logo">Rubin</a> Das Logo der Webseite, das als Link zur Startseite dient -->
    <a href="../index.php" class="logo">
        <!-- <img src="image/logo.png" alt="Rubin Logo" class="logo-img"> -->
        <img src="../images/rubin-hood3.png" alt="Rubin Logo" class="logo-img">
    </a>        
    <nav class="nav-links"> <!-- Navigationselement, das die Links zu den verschiedenen Seiten enthält -->
        <a href="../index.php">Home</a> <!-- Link zur Startseite -->
        <a href="../navigation/about.php">About</a> <!-- Link zur "Über uns"-Seite -->
        <a href="../navigation/projects.php">My Projects</a> <!-- Link zur Seite mit den angebotenen Diensten -->
        <a href="../navigation/contact.php">Contact</a> <!-- Link zur Kontaktseite -->
        <a href="../navigation/notion.php">Notion</a> <!-- Link zur Startseite -->
    </nav>
    
    <div class="social-icons"> <!-- Container für die Social-Media-Icons -->
        <a href="https://github.com/rubin-hood" class="fa-brands fa-github"></a> <!-- Link zu GitHub mit dem entsprechenden Icon -->
        <a href="#" class="fa-brands fa-twitter"></a> <!-- Link zu Twitter mit dem entsprechenden Icon -->
        <a href="https://www.youtube.com/@Rubin-Hood/videos" class="fa-brands fa-youtube"></a> <!-- Link zu YouTube mit dem entsprechenden Icon -->
    </div>
    <div class="hamburger-menu"> <!-- Hamburger-Menü für mobile Geräte -->
        <span></span> <!-- Die drei Balken des Hamburger-Menüs -->
        <span></span>
        <span></span>
    </div>
</header>


<div class="mobile-menu"> <!-- Mobiles Menü, das auf kleinen Bildschirmen angezeigt wird -->
    <span class="close-menu">&times;</span> <!-- Schließen-Symbol -->
    <nav> <!-- Navigationselement für das mobile Menü -->
        <a href="../index.php">Home</a> <!-- Link zur Startseite -->
        <a href="../navigation/about.php">About</a> <!-- Link zur "Über uns"-Seite -->
        <a href="../navigation/projects.php">My Projects</a> <!-- Link zur Seite mit den angebotenen Diensten -->
        <a href="../navigation/contact.php">Contact</a>
        <!-- <a href="#">Kontakt</a> -->
    </nav>
    <div class="mobile-social-icons"> <!-- Container für die Social-Media-Icons im mobilen Menü -->
        <a href="https://github.com/rubin-hood" class="fa-brands fa-github"></a> <!-- Link zu GitHub mit dem entsprechenden Icon -->
        <a href="#" class="fa-brands fa-twitter"></a> <!-- Link zu Twitter mit dem entsprechenden Icon -->
        <a href="https://www.youtube.com/@Rubin-Hood/videos" class="fa-brands fa-youtube"></a> <!-- Link zu YouTube mit dem entsprechenden Icon -->
    </div>
</div>
<!------------------------------------------ Ende des Header ------------------------------------------>