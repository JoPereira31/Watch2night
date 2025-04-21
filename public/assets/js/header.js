// Quand le contenu de la page est complètement chargé
document.addEventListener('DOMContentLoaded', () => {
  const btnOpenSearch = document.querySelector('.btnOpenSearch'); // Bouton pour ouvrir la barre de recherche
  const barreRecherche = document.querySelector('.barreRecherche'); // La barre de recherche elle-même

  // Vérifie que les éléments existent bien
  if (btnOpenSearch && barreRecherche) {
    btnOpenSearch.addEventListener('click', () => {
      // Si l'écran fait moins de 768px (mobile), on active/désactive la barre de recherche
      if (window.innerWidth < 768) {
        barreRecherche.classList.toggle('active'); // Ajoute ou enlève la classe 'active'
      }
    });
  }
});