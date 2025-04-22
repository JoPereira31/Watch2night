// URL de base de l'API TMDB
const baseUrl = 'https://api.themoviedb.org/3';
// URL de base pour les images des films
const baseImg = 'https://image.tmdb.org/t/p/w500';
// Clé API pour accéder à l'API TMDB
const cleApi = '70c2c989d45f4f1b34cd5f4bc10b9f05';

// ---- Fonctions API ----

/* 
* appelerApi : fait un appel HTTP GET vers une URL et retourne la réponse en JSON
* Paramètre : url (l'URL à appeler)
* Retour : les données JSON récupérées
*/
async function appelerApi(url) {
  const response = await fetch(url);
  if (!response.ok) throw new Error(`Erreur API : ${response.status}`);
  return await response.json();
}

/* 
* filmVedette : récupère le film le plus populaire actuellement
* Paramètre : aucun
* Retour : premier film populaire
*/
async function filmVedette() {
  const url = `${baseUrl}/movie/popular?language=fr&api_key=${cleApi}`;
  const data = await appelerApi(url);
  return data.results[0];
}

/* 
* detailsFilm : récupère les détails d'un film par son ID
* Paramètre : id (identifiant du film)
* Retour : les détails du film
*/
async function detailsFilm(id) {
  const url = `${baseUrl}/movie/${id}?language=fr&append_to_response=credits,videos,release_dates&api_key=${cleApi}`;
  return await appelerApi(url);
}

/* 
* genres : récupère la liste des genres de films (4 premiers)
* Paramètre : aucun
* Retour : tableau des genres
*/
async function genres() {
  const url = `${baseUrl}/genre/movie/list?language=fr&api_key=${cleApi}`;
  const data = await appelerApi(url);
  return data.genres.slice(0, 4);
}

/* 
* filmsParGenre : récupère des films triés par popularité dans un genre donné
* Paramètre : idGenre (ID du genre), limit (nombre maximum de films)
* Retour : tableau de films
*/
async function filmsParGenre(idGenre, limit = 5) {
  const url = `${baseUrl}/discover/movie?language=fr&sort_by=popularity.desc&with_genres=${idGenre}&api_key=${cleApi}`;
  const data = await appelerApi(url);
  return data.results.slice(0, limit);
}

/* 
* rechercheParTitre : cherche des films correspondant à un titre
* Paramètre : query (texte recherché)
* Retour : tableau de films trouvés
*/
async function rechercheParTitre(query) {
  const url = `${baseUrl}/search/movie?query=${encodeURIComponent(query)}&language=fr&api_key=${cleApi}`;
  const data = await appelerApi(url);
  return data.results;
}

/* 
* rechercheFilmsParActeur : cherche des films par nom d'acteur
* Paramètre : nom (nom de l'acteur)
* Retour : tableau de films liés à l'acteur
*/
async function rechercheFilmsParActeur(nom) {
  const url = `${baseUrl}/search/person?query=${encodeURIComponent(nom)}&language=fr&api_key=${cleApi}`;
  const data = await appelerApi(url);
  let films = [];
  for (const personne of data.results) {
    if (personne.known_for) {
      personne.known_for.forEach(film => {
        if (film.media_type === 'movie' && film.poster_path) {
          films.push(film);
        }
      });
    }
  }
  return films;
}

// ---- Fonction Générique Affichage Infos Film ----

/* 
* afficherInfosFilm : affiche les informations principales d'un film dans un container HTML
* Paramètre : film (objet film complet), container (élément HTML où injecter les infos)
*/
function afficherInfosFilm(film, container) {
  let realisateur = 'Inconnu';

  if (film.credits && film.credits.crew) {
    const real = film.credits.crew.find(p => p.job === 'Director');
    if (real) {
      realisateur = real.name;
    }
  }

  container.querySelector('.imageFilm').innerHTML = `
    <img src="${baseImg}${film.poster_path}" alt="${film.title}">
  `;

  container.querySelector('.infosGlobales h1').textContent = film.title;
  container.querySelector('.infosGlobales .realisateur').textContent = `${new Date(film.release_date).getFullYear()} - Réalisé par ${realisateur}`;
  container.querySelector('.infosGlobales .synopsis').textContent = film.overview;
}

// ---- Fonctions Affichage ----

/* 
* afficherFilmVedette : affiche le film le plus populaire sur la page d'accueil
* Paramètre : aucun
*/
async function afficherFilmVedette() {
  const filmPopulaire = await filmVedette();
  const details = await detailsFilm(filmPopulaire.id);
  const container = document.querySelector('#filmVedette');

  afficherInfosFilm(details, container);

  const acteurs = details.credits.cast.slice(0, 4);
  const nomsActeurs = acteurs.map(a => a.name).join(', ');

  container.querySelector('.listeActeurs').textContent = `Avec : ${nomsActeurs}`;
  container.querySelector('.voirDetails').href = `/jordan-pereira/Watch2Night/film?id=${details.id}`;
}

/* 
* afficherGenres : affiche des sections de films triés par genre
* Paramètre : aucun
*/
async function afficherGenres() {
  const genreList = await genres();
  const container = document.querySelector('#genresContainer');

  for (const genre of genreList) {
    const films = await filmsParGenre(genre.id);

    const section = document.createElement('section');
    section.classList.add('genreSection');

    const titre = document.createElement('h2');
    titre.textContent = genre.name;
    section.appendChild(titre);

    const carousel = document.createElement('div');
    carousel.classList.add('genreCarousel');

    films.forEach(film => {
      const card = document.createElement('a');
      card.href = `/jordan-pereira/Watch2Night/film?id=${film.id}`;
      card.classList.add('filmCard');

      const img = document.createElement('img');
      img.src = `${baseImg}${film.poster_path}`;
      img.alt = film.title;

      const titreFilm = document.createElement('p');
      titreFilm.textContent = film.title;

      card.appendChild(img);
      card.appendChild(titreFilm);
      carousel.appendChild(card);
    });

    section.appendChild(carousel);
    container.appendChild(section);
  }
}

/* 
* afficherResultatsRecherche : affiche les résultats d'une recherche par titre ou acteur
* Paramètre : aucun (prend l'info depuis l'URL)
*/
async function afficherResultatsRecherche() {
  const params = new URLSearchParams(window.location.search);
  const query = params.get('query');
  if (!query) return;

  const titreRecherche = document.querySelector('#pageRecherche h2');
  const container = document.querySelector('#resultatsRecherche');

  titreRecherche.textContent = `Résultats pour : "${query}"`;
  container.innerHTML = '';

  const [filmsParTitre, filmsParActeur] = await Promise.all([
    rechercheParTitre(query),
    rechercheFilmsParActeur(query)
  ]);

  const filmsMap = new Map();
  [...filmsParTitre, ...filmsParActeur].forEach(film => {
    if (!filmsMap.has(film.id)) {
      filmsMap.set(film.id, film);
    }
  });

  const films = Array.from(filmsMap.values());

  if (films.length === 0) {
    container.innerHTML = '<p>Aucun résultat trouvé.</p>';
    return;
  }

  films.forEach(film => {
    const card = document.createElement('a');
    card.href = `film?id=${film.id}`;

    card.classList.add('filmCard');

    const img = document.createElement('img');
    img.src = film.poster_path ? `${baseImg}${film.poster_path}` : 'assets/img/placeholder.jpg';
    img.alt = film.title;

    const titreFilm = document.createElement('p');
    titreFilm.textContent = film.title;

    card.appendChild(img);
    card.appendChild(titreFilm);
    container.appendChild(card);
  });
}

/* 
* chargerDetailsFilm : affiche tous les détails d'un film sélectionné
* Paramètre : aucun (prend l'info depuis l'URL)
*/
async function chargerDetailsFilm() {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');
  if (!id) return;

  try {
    const details = await detailsFilm(id);
    const container = document.querySelector('#detailsFilm');

    afficherInfosFilm(details, container);

    const acteurs = details.credits.cast.slice(0, 4);
    const acteursHTML = acteurs.map(a => `
      <div class="carteActeur">
        <img src="${baseImg}${a.profile_path}" alt="${a.name}">
        <p>${a.name}</p>
      </div>
    `).join('');
    container.querySelector('.listeActeurs').innerHTML = acteursHTML;

    const bandeAnnonce = details.videos.results.find(v => v.type === 'Trailer');
    const desktopContainer = container.querySelector('.bandeAnnonceDesktop');

    if (bandeAnnonce) {
      const bandeAnnonceId = bandeAnnonce.key;
      const youtubeEmbed = `https://www.youtube.com/embed/${bandeAnnonceId}`;
      desktopContainer.innerHTML = `<iframe src="${youtubeEmbed}" allowfullscreen></iframe>`;
    } else {
      desktopContainer.innerHTML = `<p style="text-align:center;">Aucune bande-annonce disponible pour ce film.</p>`;
    }

    initialiserBoutonWatchlist();

  } catch (error) {
    console.error('Erreur chargement film :', error);
  }
}

// ---- Chargement Auto ----

/* 
* Lorsque le document est chargé, appelle automatiquement les fonctions nécessaires selon la page
*/
document.addEventListener('DOMContentLoaded', async () => {
  if (document.querySelector('#filmVedette')) afficherFilmVedette();
  if (document.querySelector('#genresContainer')) afficherGenres();
  if (document.querySelector('#detailsFilm')) chargerDetailsFilm();
  if (document.querySelector('#resultatsRecherche')) afficherResultatsRecherche();
});
