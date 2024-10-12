<?php 

include '../layouts/header.php'; ?>

<main id="content">
<div class="content"> <!-- Start des Content-Bereichs -->
<div class="blogbeitrag">
    <section>

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