<?php 

include '../layouts/header.php'; ?>

<main id="content">
<div class="content"> <!-- Start des Content-Bereichs -->
<div class="blogbeitrag">
    <section>

    <p>Wenn du Fragen hast, Anregungen geben möchtest oder einfach nur eine Nachricht hinterlassen willst, bist du hier genau richtig.
    Ich freue mich, von dir zu hören! Fülle einfach das untenstehende Formular aus und ich werde mich so schnell wie möglich bei dir melden.</p>
    <p>Ich bemühe mich, auf alle Nachrichten innerhalb von 1-2 Werktagen zu antworten.</p>
    <p>Vielen Dank für dein Interesse und deine Zeit!</p>

    <form action="../php/kontakt.php" method="POST">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" required><br><br>

  <label for="email">E-Mail:</label>
  <input type="email" id="email" name="email" required><br><br>

  <label for="message">Nachricht:</label><br>
  <textarea id="message" name="message" rows="5" required></textarea><br><br>

  <input type="submit" value="Senden">
</form>


    </section>
    </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>