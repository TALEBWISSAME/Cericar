<?php
$this->title = 'Accueil';
?>
<!-- Section Chercher un voyage -->
<div class="container text-center my-5 py-4">
    <h2 class="mb-4" style="color: #2c3e50; font-weight: bold;">Trouvez votre prochain trajet</h2>
    <a href="index.php?r=recherche/index" class="btn btn-lg px-5 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 50px; font-size: 20px; font-weight: bold; box-shadow: 0 8px 20px rgba(0,0,0,0.3); transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        🔍 Chercher un voyage maintenant
    </a>
</div>

<!-- BANNIÈRE -->
<div style="width:100%;height:600px;overflow:hidden;">
    <img src="img/bg_carr.jpg" style="width:100%;height:100%;object-fit:cover;">
</div>

<!-- TRAJETS POPULAIRES -->
<div class="container mt-5">
    <h2 class="fw-bold mb-4" style="color:#004b50;">Les trajets populaires</h2>
    <div class="row g-4">
        <?php
        $trajets = [
            ['img'=>'paris2.png','titre'=>'Orléans → Paris','prix'=>'4,49 €'],
            ['img'=>'lyon.png','titre'=>'Paris → Lyon','prix'=>'10,99 €'],
            ['img'=>'pariss.png','titre'=>'Lyon → Paris','prix'=>'10,99 €'],
            ['img'=>'reims.png','titre'=>'Paris → Reims','prix'=>'5,49 €'],
        ];
        foreach ($trajets as $t): ?>
            <div class="col-md-3">
                <div class="card card-popular shadow border-0">
                    <img src="img/<?= $t['img'] ?>" class="card-img-top" style="height:230px;object-fit:cover;">
                    <div class="card-body">
                        <h5 class="fw-bold"><?= $t['titre'] ?></h5>
                        <p class="text-secondary">Dès <b><?= $t['prix'] ?></b></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- SOCIAL -->
<div class="social-section text-center my-5">
    <h4 class="fw-bold" style="color:#004b50;">Suivez-nous</h4>
    <div class="d-flex justify-content-center gap-4 mt-3">
        <a href="#" class="social-icon facebook"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-icon instagram"><i class="bi bi-instagram"></i></a>
        <a href="#" class="social-icon whatsapp"><i class="bi bi-whatsapp"></i></a>
    </div>
</div>
