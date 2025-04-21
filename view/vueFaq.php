<?php 
$titrePage = "Contact - Watch2Night";
$descriptionPage = "Envoyez-nous un message via notre formulaire de contact sécurisé.";

include_once RACINE . '/view/layout/head.php';
include_once RACINE . '/view/layout/header.php';
?>

<main id="contactPage">

  <section class="contactContainer">
    <main id="pageContact">
    <section class="carteContact">
        <h2>Nous contacter</h2>
        <form action="contact" method="POST">
            <div class="groupe">
                <label for="nom">Votre nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="groupe">
                <label for="email">Votre email :</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="groupe">
                <label for="message">Votre message :</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>

            <button type="submit">Envoyer</button>
        </form>
    </section>
</main>



</main>

<?php include_once RACINE . '/view/layout/footer.php'; ?>
