document.addEventListener('DOMContentLoaded', () => {
  // Récupère l'id du film depuis l'URL
  const idFilm = new URLSearchParams(window.location.search).get('id');
  const idUtilisateurConnecte = window.idUtilisateurConnecte ?? null; // Récupère l'utilisateur connecté s'il existe

  // Si un idFilm est présent, on charge les commentaires
  if (idFilm) {
    chargerCommentaires(idFilm);
  }

  // Gestion de l'ajout de commentaire
  const form = document.getElementById('formCommentaire');
  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault(); // Empêche le rechargement de la page

      const contenu = document.getElementById('contenuCommentaire').value.trim(); // Récupère le contenu du commentaire

      if (!contenu) return; // Si vide, on ne fait rien

      try {
        // Envoi du commentaire via fetch
        const res = await fetch('commentaire', {
          method: 'POST',
          body: new URLSearchParams({ id_film: idFilm, contenu })
        });
        const result = await res.json();

        if (result.success) {
          // Si ajout réussi, on vide le champ et recharge les commentaires
          document.getElementById('contenuCommentaire').value = '';
          await chargerCommentaires(idFilm);
        } else {
          // Affiche une erreur en cas d'échec
          alert(result.error || "Erreur lors de l'ajout.");
        }
      } catch (e) {
        console.error("Erreur ajout commentaire :", e);
      }
    });
  }
});

// Variables pour modales de suppression et modification de commentaire
let idCommentaireAEditer = null;
const modalSuppression = document.getElementById('modalSuppressionCommentaire');
const modalModification = document.getElementById('modalModificationCommentaire');
const btnConfirmerSuppression = document.getElementById('confirmerSuppression');
const btnAnnulerSuppression = document.getElementById('annulerSuppression');
const btnConfirmerModification = document.getElementById('confirmerModification');
const btnAnnulerModification = document.getElementById('annulerModification');
const inputNouveauContenu = document.getElementById('nouveauContenu');

// Fonction pour charger les commentaires
async function chargerCommentaires(idFilm) {
  try {
    const response = await fetch(`commentaire&id_film=${idFilm}`); // Appelle l'API pour récupérer les commentaires
    const commentaires = await response.json();

    const container = document.getElementById('listeCommentaires');
    container.innerHTML = ''; // Vide l'affichage actuel

    if (!commentaires.length) {
      container.innerHTML = '<p>Aucun commentaire pour ce film.</p>'; // Message si aucun commentaire
      return;
    }

    // Parcours des commentaires pour les afficher
    commentaires.forEach(c => {
      const bloc = document.createElement('div');
      bloc.className = 'commentaire';

      bloc.innerHTML = `
        <p class="auteur">${c.pseudo}<span> (${formatDate(c.date_com)})</span></p>
        <div class="contenuEtActions">
          <p class="contenu">${c.contenu}</p>
          ${idUtilisateurConnecte == c.id_utilisateur ? `
            <div class="actionsCommentaire">
              <button class="btnModifier" data-id="${c.id_com}" title="Modifier"><i class="fas fa-pen"></i></button>
              <button class="btnSupprimer" data-id="${c.id_com}" title="Supprimer"><i class="fas fa-trash"></i></button>
            </div>
          ` : ''}
        </div>
      `;

      container.appendChild(bloc); // Ajoute le commentaire à la liste
    });

  } catch (error) {
    console.error("Erreur chargement commentaires :", error);
  }
}

// Fonction pour formater une date en français
function formatDate(dateString) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('fr-FR', options);
}

// Ecouteur global pour détecter les clics sur les boutons modifier/supprimer
document.addEventListener('click', (e) => {
  const btnModifier = e.target.closest('.btnModifier');
  const btnSupprimer = e.target.closest('.btnSupprimer');

  if (btnSupprimer) {
    idCommentaireAEditer = btnSupprimer.dataset.id; // Stocke l'id du commentaire à supprimer
    modalSuppression.classList.remove('hidden'); // Affiche la modale de suppression
  }

  if (btnModifier) {
    idCommentaireAEditer = btnModifier.dataset.id; // Stocke l'id du commentaire à modifier
    const ancienContenu = btnModifier.closest('.commentaire').querySelector('.contenu').textContent.trim();
    inputNouveauContenu.value = ancienContenu; // Remplit l'input avec l'ancien contenu
    modalModification.classList.remove('hidden'); // Affiche la modale de modification
  }
});

// Gestion des boutons de suppression de commentaire
if (btnConfirmerSuppression) {
  btnConfirmerSuppression.addEventListener('click', async () => {
    if (!idCommentaireAEditer) return;

    try {
      await fetch('commentaire', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'supprimer_commentaire',
          id_commentaire: idCommentaireAEditer
        })
      });
      modalSuppression.classList.add('hidden'); // Cache la modale
      const idFilm = new URLSearchParams(window.location.search).get('id');
      await chargerCommentaires(idFilm); // Recharge les commentaires
    } catch (error) {
      console.error('Erreur suppression :', error);
    }
  });
}

// Annuler la suppression
if (btnAnnulerSuppression) {
  btnAnnulerSuppression.addEventListener('click', () => {
    modalSuppression.classList.add('hidden'); // Cache la modale
  });
}

// Gestion des boutons de modification de commentaire
if (btnConfirmerModification) {
  btnConfirmerModification.addEventListener('click', async () => {
    if (!idCommentaireAEditer) return;

    const nouveauContenu = inputNouveauContenu.value.trim();
    if (!nouveauContenu) return; // Si nouveau contenu vide, rien faire

    try {
      await fetch('commentaire', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'modifier_commentaire',
          id_commentaire: idCommentaireAEditer,
          contenu: nouveauContenu
        })
      });
      modalModification.classList.add('hidden'); // Cache la modale
      const idFilm = new URLSearchParams(window.location.search).get('id');
      await chargerCommentaires(idFilm); // Recharge les commentaires
    } catch (error) {
      console.error('Erreur modification :', error);
    }
  });
}

// Annuler la modification
if (btnAnnulerModification) {
  btnAnnulerModification.addEventListener('click', () => {
    modalModification.classList.add('hidden'); // Cache la modale
  });
}