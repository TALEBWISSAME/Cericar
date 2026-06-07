<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = "Liste des Internautes";
?>

<h1 style="margin: 20px 0;">Liste des Internautes</h1>

<div style="
    display: flex;
    flex-direction: column;
    gap: 25px;
">
<?php foreach ($internautes as $u): ?>
    
    <div style="
        display: flex;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
        width: 80%;
    ">
        <!-- Image -->
        <img src="<?= $u->photo ?>" 
             alt="photo"
             style="width: 100px; height: 100px; border-radius: 10px; object-fit: cover; margin-right: 20px;">

        <!-- Infos -->
        <div>
            <h3 style="margin: 0;"><?= Html::encode($u->pseudo) ?></h3>

            <!-- Bouton détails -->
            <?= Html::a(
                "Voir détails",
                ['test/afficher-internaute', 'pseudo' => $u->pseudo],
                [
                    'style' => '
                        display:inline-block;
                        margin-top:8px;
                        padding:6px 12px;
                        background:#ffd9e6;
                        color:black;
                        border-radius:6px;
                        text-decoration:none;
                        font-weight:bold;
                    '
                ]
            ) ?>
        </div>
    </div>

<?php endforeach; ?>
</div>
