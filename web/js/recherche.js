// web/js/recherche.js

console.log("recherche.js chargé");

// Vérifier que page et formulaire sont bien chargés
$(document).ready(function () {

    // ========== RECHERCHE ==========
    // Intercepter la soumission du formulaire (délégation pour pages chargées en AJAX)
    $(document).on('submit', '#form-recherche', function (e) {
        e.preventDefault(); // Empêche le rechargement complet de la page

        // Où envoyer les requêtes et quelles données envoyer
        var urlCible = $(this).attr('action') || 'index.php';
        var donneesFormulaire = $(this).serialize();

        // Faire appel AJAX et remplacer uniquement la zone des résultats
        $.ajax({
            url: urlCible,
            type: 'GET',
            data: donneesFormulaire,
            dataType: 'json',
            success: function (response) {
                // 1) Sélectionner le div qui contient les résultats
                $('#zone-resultats').html(response.html).hide().fadeIn();

                // 2) Mettre à jour le bandeau de notification global
                $('#notif')
                    .text(response.message)
                    .show();
            },
            error: function () {
                $('#notif')
                    .text('Une erreur est survenue lors de la recherche.')
                    .show();
            }
        });
    });

    // ========== RÉSERVATION ==========
    $(document).on('click', '.btn-reserver', function () {
        var $btn = $(this); // Sauvegarder la référence au bouton
        var voyageId = $btn.data('voyage-id');
        var nbPlaces = $btn.data('nb-places');

        if (!confirm('Confirmer la réservation de ' + nbPlaces + ' place(s) ?')) {
            return;
        }

        // Désactiver le bouton pendant la requête
        $btn.prop('disabled', true).text('Réservation en cours...');

        $.ajax({
            url: 'index.php?r=reservation/reserver-ajax',
            type: 'POST',
            data: {
                voyage_id: voyageId,
                nb_places: nbPlaces,
                // CSRF token récupéré depuis le meta tag
                _csrf: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
       success: function (response) {
    // Afficher la notification
    $('#notif')
        .text(response.message)
        .removeClass('alert-success alert-danger')
        .addClass(response.ok ? 'alert-success' : 'alert-danger')
        .show()
        .delay(3000)
        .fadeOut();

    if (response.ok) {
        // ✅ RECHARGER AUTOMATIQUEMENT LA RECHERCHE
        // Soumettre à nouveau le formulaire pour mettre à jour les places
        setTimeout(function() {
            $('#form-recherche').submit();
        }, 1500);
    } else {
        // Réactiver le bouton en cas d'erreur
        $btn.prop('disabled', false).text('Réserver');
    }
},

    
        
            error: function () {
                $('#notif')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text('Erreur lors de la réservation.')
                    .show();
                
                // Réactiver le bouton
                $btn.prop('disabled', false).text('Réserver ce voyage');
            }
        });
    });
});
