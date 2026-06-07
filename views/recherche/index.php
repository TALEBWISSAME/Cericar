=<?php
/** @var yii\web\View $this */
/** @var app\models\Voyage[] $voyages */
/** @var int|null $nb */
/** @var string|null $depart */
/** @var string|null $arrivee */
/** @var string|null $message */

use yii\helpers\Html;

$this->title = 'Recherche de voyage';
?>

<style>
    .form-recherche-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .form-recherche-container h1 {
        color: #004b50;
        font-weight: 700;
        margin-bottom: 30px;
        text-align: center;
    }

    .form-recherche-container label {
        font-weight: 600;
        color: #004b50;
        margin-bottom: 5px;
    }

    .form-recherche-container input {
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .form-recherche-container input:focus {
        border-color: #00aee0;
        box-shadow: 0 0 10px rgba(0, 174, 224, 0.2);
        transform: scale(1.02);
    }

    .form-recherche-container button {
        background: #00aee0;
        border: none;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: bold;
        color: white;
        transition: all 0.3s ease;
    }

    .form-recherche-container button:hover {
        background: #0089aa;
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* ✅ Style pour le bandeau de notification */
    #notif {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        display: none;
    }
</style>

<!-- ✅ Bandeau de notification -->
<div id="notif" class="alert" role="alert"></div>

<div class="form-recherche-container">
    <h1>Recherche de voyage</h1>

    <!-- ✅ AJOUTÉ : id="form-recherche" -->
    <form method="get" action="index.php" id="form-recherche">
        <input type="hidden" name="r" value="recherche/index">
        
        <label>Ville de départ</label><br>
        <input type="text" name="depart" value="<?= Html::encode($depart ?? '') ?>"><br><br>
        
        <label>Ville d'arrivée</label><br>
        <input type="text" name="arrivee" value="<?= Html::encode($arrivee ?? '') ?>"><br><br>
        
        <label>Nombre de personnes</label><br>
        <input type="number" name="nb" min="1" value="<?= $nb ?? 1 ?>"><br><br>
        
        <button type="submit">Rechercher</button>
    </form>
</div>

<hr>

<!-- Zone des résultats -->
<div id="zone-resultats">
    <?php
    echo $this->render('test', [
        'voyages'        => $voyages ?? [],
        'placesDemandes' => $nb ?? 1,
        'message'        => $message ?? null,
    ]);
    ?>
</div>
