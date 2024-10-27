<?php 

include '../layouts/header.php'; ?>

<main id="content">
<div class="content"> <!-- Start des Content-Bereichs -->
<div class="blogbeitrag">
    <section>

    <p>If you have questions, want to give suggestions, or simply want to leave a message, you're in the right place. I look forward to hearing from you! Simply fill out the form below and I will get back to you as soon as possible.</p>
    <p>Thank you for your interest and your time!</p>

    <form action="../php/kontakt.php" method="POST">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" required><br><br>

  <label for="email">E-Mail:</label>
  <input type="email" id="email" name="email" required><br><br>

  <label for="message">Message:</label><br>
  <textarea id="message" name="message" rows="5" required></textarea><br><br>

  <input type="submit" value="Senden">
</form>


    </section>
    </div>
    </div>
</main>

<?php include '../layouts/footer.php'; ?>