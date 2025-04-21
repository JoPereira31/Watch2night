// Effet flip carte
const carte = document.querySelector('.carteAuthentification'); // Sélection de la carte d'authentification
const btnFlip = document.querySelectorAll('.btnFlip'); // Boutons qui déclenchent l'effet flip

// Ajoute un écouteur d'événement sur chaque bouton flip
btnFlip.forEach(btn => {
  btn.addEventListener('click', () => {
    carte.classList.toggle('active'); // Bascule la classe 'active' pour animer le flip
  });
});

// Vérification des mots de passe lors de l'inscription
const formInscription = document.getElementById('formInscription'); // Formulaire d'inscription

if (formInscription) {
  formInscription.addEventListener('submit', function(event) {
    const mdp = document.getElementById('mot_de_passe').value; // Récupération du mot de passe
    const confirm = document.getElementById('confirmation').value; // Récupération de la confirmation
    const errorContainer = document.getElementById('erreurMdp'); // Conteneur pour afficher l'erreur

    if (mdp !== confirm) {
      event.preventDefault(); // Empêche l'envoi du formulaire
      errorContainer.textContent = "Les mots de passe ne correspondent pas."; // Message d'erreur
      errorContainer.style.display = "block"; // Affiche l'erreur
    }
  });
}

// Activation du flip automatique si l'URL contient 'action=inscription'
if (window.location.search.includes('action=inscription')) {
  carte.classList.add('active'); // Ajoute directement la classe 'active' pour afficher l'inscription
}