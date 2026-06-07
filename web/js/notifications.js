/**
 * Affiche un bandeau de notification temporaire à l’écran
 *
 * @param {string} message - Message à afficher
 * @param {string} type - Type de notification ('success' ou 'error')
 */
function afficherNotification(message, type = 'success') {
    // Récupération de l'élément HTML du bandeau
    const bandeau = document.getElementById('bandeau-notification');

    // Contenu du message
    bandeau.textContent = message;

    // Application du style selon le type de notification
    bandeau.className = (type === 'success') ? 'notif-success' : 'notif-error';

    // Affichage du bandeau
    bandeau.style.display = 'block';

    // Masquage automatique après 4 secondes
    setTimeout(() => {
        bandeau.style.display = 'none';
    }, 4000);
}
