<?php
// Titre de la page
$this->title = 'Inscription';

// Récupération des informations CSRF (sécurité)
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();
?>

<!-- ================= STYLE DU FORMULAIRE ================= -->
<style>
    /* Conteneur principal du formulaire */
    .form-inscription-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* Titre */
    .form-inscription-container h1 {
        color: #004b50;
        font-weight: 700;
        margin-bottom: 30px;
    }

    /* Champs du formulaire */
    .form-inscription-container .form-control {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px;
        transition: all 0.3s ease;
    }

    /* Effet focus */
    .form-inscription-container .form-control:focus {
        border-color: #00aee0;
        box-shadow: 0 0 10px rgba(0, 174, 224, 0.2);
        transform: scale(1.02);
    }

    /* Labels */
    .form-inscription-container .form-label {
        font-weight: 600;
        color: #004b50;
    }

    /* Bouton */
    .form-inscription-container .btn-primary {
        background: #00aee0;
        border: none;
        border-radius: 30px;
        padding: 14px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .form-inscription-container .btn-primary:hover {
        background: #0089aa;
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
</style>

<!-- ================= FORMULAIRE D'INSCRIPTION ================= -->
<div class="form-inscription-container">
    <h1 class="text-center">Inscription</h1>

    <!-- Formulaire POST -->
    <form id="form-inscription" method="post" class="row g-3">

        <!-- Champ CSRF obligatoire -->
        <input type="hidden" name="<?= $csrfParam ?>" value="<?= $csrfToken ?>">

        <!-- Pseudo -->
        <div class="col-md-6">
            <label class="form-label">Pseudo *</label>
            <input type="text" name="Internaute[pseudo]" class="form-control" required>
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <label class="form-label">Email *</label>
            <input type="email" name="Internaute[mail]" class="form-control" required>
        </div>

        <!-- Mot de passe -->
        <div class="col-md-6">
            <label class="form-label">Mot de passe *</label>
            <input type="password" name="Internaute[pass]" class="form-control" required>
        </div>

        <!-- Nom -->
        <div class="col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" name="Internaute[nom]" class="form-control">
        </div>

        <!-- Prénom -->
        <div class="col-md-6">
            <label class="form-label">Prénom</label>
            <input type="text" name="Internaute[prenom]" class="form-control">
        </div>

        <!-- Permis -->
        <div class="col-md-6">
            <label class="form-label">Numéro de permis</label>
            <input type="text" name="Internaute[permis]" class="form-control">
        </div>

        <!-- Photo -->
        <div class="col-12">
            <label class="form-label">Photo (URL)</label>
            <input type="text" name="Internaute[photo]" class="form-control">
        </div>

        <!-- Bouton de soumission -->
        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
                Créer mon compte
            </button>
        </div>
    </form>
</div>
