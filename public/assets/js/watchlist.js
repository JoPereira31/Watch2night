/* 
* afficherPopup : crée et affiche un petit message temporaire à l'écran
* Paramètre : message (le texte à afficher dans la popup)
*/
function afficherPopup(message) {
  const popup = document.createElement('div'); // Crée un nouvel élément div
  popup.className = 'popupWatchlist'; // Ajoute la classe pour le style
  popup.textContent = message; // Met le texte du message
  document.body.appendChild(popup); // Ajoute la popup à la page

  // Après 2 secondes, la popup disparaît
  setTimeout(() => {
    popup.remove();
  }, 2000);
}

/* 
* initialiserBoutonWatchlist : prépare le bouton "Watchlist" pour ajouter/retirer un film
* Paramètre : aucun
*/
function initialiserBoutonWatchlist() {
  const btnWatchlist = document.querySelector('.btnWatchlist'); // Sélectionne le bouton
  if (!btnWatchlist) return; // S'il n'existe pas, on arrête

  let enCours = false; // Sert à éviter plusieurs clics rapides

  // Écouteur sur le bouton Watchlist
  btnWatchlist.addEventListener('click', async () => {
    if (enCours) return; // Si déjà en cours, on ne refait pas
    enCours = true; // Passe en "en cours"

    // Récupère les données du film depuis la page
    const idFilm = btnWatchlist.dataset.idfilm;
    const titreFilm = document.querySelector('h1') ? document.querySelector('h1').textContent : '';
    const afficheFilm = document.querySelector('.imageFilm img') ? document.querySelector('.imageFilm img').getAttribute('src') : '';

    try {
      // Envoie une requête POST pour ajouter ou retirer le film
      const response = await fetch('watchlist', {
        method: 'POST',
        body: new URLSearchParams({
          id_film: idFilm,
          titre: titreFilm,
          affiche: afficheFilm
        })
      });

      const result = await response.json();

      // Selon la réponse du serveur, on ajoute ou enlève l'active + popup
      if (result.status === 'ajoute') {
        btnWatchlist.classList.add('active');
        afficherPopup('Ajouté à la watchlist');
      } else if (result.status === 'retire') {
        btnWatchlist.classList.remove('active');
        afficherPopup('Retiré de la watchlist');
      }

    } catch (error) {
      console.error('Erreur Watchlist:', error); // En cas d'erreur
    }

    enCours = false; // Remet le bouton à disponible
  });
}
