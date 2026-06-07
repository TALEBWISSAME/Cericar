<?php
$this->title = 'Connexion';
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->getCsrfToken();
?>

<style>
    .form-connexion-container {
        max-width: 450px;
        margin: 80px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .form-connexion-container h1 {
        color: #004b50;
        font-weight: 700;
        margin-bottom: 30px;
    }

    .form-connexion-container .form-control {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .form-connexion-container .form-control:focus {
        border-color: #00aee0;
        box-shadow: 0 0 10px rgba(0, 174, 224, 0.2);
        transform: scale(1.02);
    }

    .form-connexion-container .form-label {
        font-weight: 600;
        color: #004b50;
    }

    .form-connexion-container .btn-primary {
        background: #00aee0;
        border: none;
        border-radius: 30px;
        padding: 14px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .form-connexion-container .btn-primary:hover {
        background: #0089aa;
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
</style>
      <!-- ========== ormliare de connexion ========== -->

<div class="form-connexion-container">
    <h1 class="text-center">Connexion</h1>

    <form id="form-connexion" method="post" class="row g-3">
        <input type="hidden" name="<?= $csrfParam ?>" value="<?= $csrfToken ?>">

        <div class="col-12">
            <label class="form-label">Pseudo</label>
            <input type="text" name="pseudo" class="form-control" required>
        </div>

        <div class="col-12">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="pass" class="form-control" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </div>
    </form>
</div>
