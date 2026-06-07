/**
 * FICHIER JAVASCRIPT : internaute.js
 * 
 * Ce fichier gère les interactions AJAX pour :
 * - L'inscription d'un nouvel utilisateur
 * - La connexion d'un utilisateur existant
 * 
 * Utilise jQuery pour simplifier les requêtes AJAX et la manipulation du DOM.
 */

// Message dans la console du navigateur pour confirmer que le fichier est chargé
console.log("internaute.js chargé");

/**
 * $(document).ready() : Fonction jQuery qui s'exécute quand la page est complètement chargée
 * 
 * Tout le code à l'intérieur ne s'exécutera que quand :
 * - Le HTML est chargé
 * - Les éléments du DOM sont accessibles
 * 
 * C'est important pour éviter les erreurs "élément introuvable"
 */
$(document).ready(function () {

    // ========================================
    // GESTION DE L'INSCRIPTION
    // ========================================
    
    /**
     * ÉVÉNEMENT : Soumission du formulaire d'inscription
     * 
     * $('#form-inscription') = sélectionne le formulaire avec id="form-inscription"
     * .on('submit', ...) = écoute l'événement "soumission du formulaire"
     */
    $(document).on('submit', '#form-inscription', function (e) {
        
        // e.preventDefault() = empêcher le comportement par défaut
        // Sans ça, le formulaire rechargerait toute la page
        e.preventDefault();

        // RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
        // $(this) = le formulaire actuel
        // .serialize() = convertir toutes les données en format URL
        // Exemple : "pseudo=Nina&mail=nina@mail.com&pass=123&nom=Test&..."
        var formData = $(this).serialize();

        // REQUÊTE AJAX : Envoyer les données au serveur
        $.ajax({
            // URL de l'action à appeler dans le contrôleur
            url: 'index.php?r=internaute/inscription-ajax',
            
            // Méthode HTTP POST (envoyer des données)
            type: 'POST',
            
            // Données à envoyer (contenu du formulaire)
            data: formData,
            
            // Type de réponse attendue : JSON
            dataType: 'json',
            
            /**
             * FONCTION DE SUCCÈS : Appelée quand le serveur répond correctement
             * 
             * @param response Objet JSON renvoyé par le contrôleur
             * Exemple : {ok: true, message: "Inscription réussie"}
             */
            success: function (response) {
                // AFFICHAGE DE LA NOTIFICATION
                
                // $('#notif') = récupérer l'élément de notification
                $('#notif')
                    // Enlever les anciennes classes de couleur
                    .removeClass('alert-success alert-danger')
                    
                    // Ajouter la classe selon le résultat
                    // Si response.ok = true → classe verte (alert-success)
                    // Si response.ok = false → classe rouge (alert-danger)
                    .addClass(response.ok ? 'alert-success' : 'alert-danger')
                    
                    // Mettre le message renvoyé par le serveur
                    .text(response.message)
                    
                    // Afficher le bandeau de notification
                    .show();

                // SI INSCRIPTION RÉUSSIE : Réinitialiser le formulaire
                if (response.ok) {
                    // [0] = accéder à l'élément DOM natif (pas jQuery)
                    // reset() = vider tous les champs du formulaire
                    $('#form-inscription')[0].reset();
                }
            },
            
            /**
             * FONCTION D'ERREUR : Appelée en cas d'erreur réseau
             * (serveur injoignable, timeout, erreur 500, etc.)
             */
            error: function () {
                // Afficher un message d'erreur générique
                $('#notif')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text('Une erreur est survenue lors de l\'inscription.')
                    .show();
            }
        });
    });

    // ========================================
    // GESTION DE LA CONNEXION
    // ========================================
    
    /**
     * ÉVÉNEMENT : Soumission du formulaire de connexion
     * 
     * Même principe que pour l'inscription, mais avec une redirection
     * automatique vers la page de recherche si connexion réussie.
     */
    $(document).on('submit', '#form-connexion', function (e) {
        
        // Empêcher le rechargement de la page
        e.preventDefault();

        // Récupérer les données du formulaire (pseudo et mot de passe)
        var formData = $(this).serialize();

        // REQUÊTE AJAX : Envoyer les identifiants au serveur
        $.ajax({
            // URL de l'action de connexion
            url: 'index.php?r=internaute/connexion-ajax',
            
            // Méthode POST
            type: 'POST',
            
            // Données du formulaire
            data: formData,
            
            // Réponse attendue en JSON
            dataType: 'json',
            
            /**
             * FONCTION DE SUCCÈS : Appelée quand le serveur répond
             */
            success: function (response) {
                // AFFICHAGE DE LA NOTIFICATION
                $('#notif')
                    .removeClass('alert-success alert-danger')
                    .addClass(response.ok ? 'alert-success' : 'alert-danger')
                    .text(response.message)
                    .show();

                // SI CONNEXION RÉUSSIE : Rediriger vers la page de recherche
                if (response.ok) {
                    // setTimeout() = exécuter du code après un délai
                    // Ici : attendre 1 seconde (1000 ms) puis charger sans rechargement complet
                    setTimeout(function () {
                        if (window.loadPage) {
                            window.loadPage('index.php?r=recherche/index');
                        } else {
                            window.location.href = 'index.php?r=recherche/index';
                        }
                    }, 1000);
                }
            },
            
            /**
             * FONCTION D'ERREUR : Appelée en cas d'erreur réseau
             */
            error: function () {
                $('#notif')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text('Une erreur est survenue lors de la connexion.')
                    .show();
            }
        });
    });

}); // Fin de $(document).ready()
