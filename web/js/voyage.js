// web/js/voyage.js

console.log("voyage.js chargé");

/**
 * Affiche un bandeau de notification (succès ou erreur)
 *
 * @param {string} message - Message à afficher
 * @param {string} type - 'success' ou 'error'
 */
function afficherNotification(message, type) {
    // Récupération du bandeau de notification
    var bandeau = document.getElementById('bandeau-notification');

    // Couleur selon le type
    var couleur = (type === 'success') ? '#28a745' : '#dc3545';

    if (bandeau) {
        // Message
        bandeau.textContent = message;

        // Style dynamique
        bandeau.style.background = couleur;
        bandeau.style.display = 'block';

        // Masquage automatique après 4 secondes
        setTimeout(function () {
            bandeau.style.display = 'none';
        }, 4000);
    } else {
        // Sécurité : fallback si le bandeau n'existe pas
        alert(message);
    }
}

/* =====================================================
   SOUMISSION AJAX DU FORMULAIRE DE PROPOSITION DE VOYAGE
   ===================================================== */

// Délégation d'événement (compatible avec chargement AJAX)
$(document).on('submit', '#proposer-form', function (e) {
    e.preventDefault(); // Empêche l'envoi classique du formulaire

    // Récupération des données du formulaire
    var formData = new FormData(this);

    // Envoi AJAX vers le contrôleur Yii
    fetch('index.php?r=voyage/proposer-ajax', {
        method: 'POST',
        body: formData
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.ok) {
                // Succès : notification + redirection
                afficherNotification('🎉 Voyage publié avec succès !', 'success');

                setTimeout(function () {
                    if (window.loadPage) {
                        // Navigation AJAX si disponible
                        window.loadPage('index.php');
                    } else {
                        // Fallback navigation classique
                        window.location.href = 'index.php';
                    }
                }, 2000);
            } else {
                // Erreur métier retournée par le serveur
                afficherNotification('❌ ' + data.message, 'error');
            }
        })
        .catch(function (error) {
            // Erreur réseau ou serveur
            afficherNotification('❌ Erreur réseau : ' + error, 'error');
        });
});
