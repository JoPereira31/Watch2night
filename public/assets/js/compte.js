// Quand le contenu du DOM est complètement chargé
document.addEventListener('DOMContentLoaded', () => {
  // Initialisation de la modale de suppression
  const btnOpenModal = document.getElementById('btnOpenModal'); // Bouton pour ouvrir la modale
  const btnCloseModal = document.getElementById('btnCloseModal'); // Bouton pour fermer la modale
  const modal = document.getElementById('modalSuppression'); // Modale de suppression

  // Vérifie que tous les éléments existent
  if (btnOpenModal && btnCloseModal && modal) {
    btnOpenModal.addEventListener('click', () => {
      modal.style.display = 'flex'; // Affiche la modale
    });

    btnCloseModal.addEventListener('click', () => {
      modal.style.display = 'none'; // Cache la modale
    });
  }

  // Lance le chargement de la watchlist dès que la page est prête
  loadWatchlist();
});

/* 
* loadWatchlist : charge et affiche les films présents dans la watchlist
* Paramètre : aucun
*/
async function loadWatchlist() {
  const container = document.getElementById('listeWatchlist'); // Conteneur où afficher les films
  const baseImg = 'https://image.tmdb.org/t/p/w300'; // Base URL pour les affiches de films

  // Si pas de container, on quitte
  if (!container) return;

  // Récupération des données de la watchlist stockées dans un attribut data
  let watchlistData = container.dataset.watchlist;
  let watchlist;

  try {
    // Tentative de parser les données JSON
    watchlist = JSON.parse(watchlistData);
  } catch (error) {
    console.error("Impossible de parser la watchlist :", error);
    container.innerHTML = '<p>Erreur lors du chargement de votre watchlist.</p>';
    return;
  }

  // Si la watchlist est vide ou invalide
  if (!Array.isArray(watchlist) || watchlist.length === 0) {
    container.innerHTML = '<p>Votre watchlist est vide.</p>';
    return;
  }

  // Prépare toutes les requêtes pour récupérer les détails des films
  const fetchPromises = watchlist.map(async (idFilm) => {
    try {
      const response = await fetch(`https://api.themoviedb.org/3/movie/${idFilm}?api_key=70c2c989d45f4f1b34cd5f4bc10b9f05&language=fr`);
      
      if (!response.ok) {
        console.error(`Erreur TMDB pour le film ID ${idFilm} :`, response.status);
        return null;
      }
      
      return await response.json(); // Retourne les données du film
    } catch (error) {
      console.error(`Erreur lors du chargement du film ID ${idFilm}`, error);
      return null;
    }
  });

  // Attend que toutes les requêtes soient terminées
  const films = await Promise.all(fetchPromises);

  // Affichage de chaque film dans la page
  for (const film of films) {
    if (film) {
      const card = document.createElement('a'); // Création d'un lien pour chaque film
      card.href = `film&id=${film.id}`;
      card.className = 'filmCard';
      card.innerHTML = `
        <img src="${baseImg}${film.poster_path}" alt="${film.title}">
        <p>${film.title}</p>
      `;
      container.appendChild(card); // Ajoute la carte dans le container
    }
  }
}
