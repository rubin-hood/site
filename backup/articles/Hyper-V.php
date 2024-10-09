<?php

// Header einbinden (eine Ebene höher im Verzeichnisbaum)
include '../layouts/header.php'; 
?>

<main id="content">
<div class="content"> <!-- Start des Content-Bereichs -->
<div class="blogbeitrag">
    <section>

<?php
    // Lade Parsedown-Bibliothek (eine Ebene höher im libs-Ordner)
    include '../libs/Parsedown.php';
    
    // Prüfen, ob die Parsedown-Klasse existiert
    if (class_exists('Parsedown')) {
        // Erstelle eine Parsedown-Instanz
        $Parsedown = new Parsedown();

        // Lese den Inhalt der Markdown-Datei (blogpost1.md befindet sich im selben Verzeichnis wie dieses PHP-Skript)
        $markdownContent = file_get_contents('Hyper-V.md');

        // Wandle den Markdown-Inhalt in HTML um und gib ihn aus
        echo $Parsedown->text($markdownContent);
    } else {
        echo "Parsedown wurde nicht geladen.";
    }
?>

    </section>
</div>
</div>
</main>

<?php 
// Footer einbinden (eine Ebene höher im Verzeichnisbaum)
include '../layouts/footer.php'; 
?>
