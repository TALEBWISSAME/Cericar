<?php
use yii\helpers\Html;
?>

<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <!-- Titre dynamique -->
    <title><?= Html::encode($this->title) ?></title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Variables CSRF pour les requêtes AJAX -->
    <script>
        var csrfToken = '<?= Yii::$app->request->getCsrfToken() ?>';
        var csrfParam = '<?= Yii::$app->request->csrfParam ?>';
    </script>

    <!-- Scripts JavaScript personnalisés -->
    <script src="js/recherche.js"></script>
    <script src="js/internaute.js"></script>
    <script src="js/voyage.js"></script>

    <!-- Meta -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Token CSRF pour sécurité -->
    <meta name="csrf-token" content="<?= Yii::$app->request->getCsrfToken() ?>">
    <?php $this->registerCsrfMetaTags(); ?>

    <?php $this->head(); ?>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- CSS global -->
    <style>
        /* ====== EFFETS INPUT ====== */
        .search-input {
            transition: all 0.2s ease-in-out;
        }
        .search-input:hover,
        .search-input:focus {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* ====== CARTES ====== */
        .card-popular {
            border-radius: 18px;
            overflow: hidden;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            cursor: pointer;
        }
        .card-popular:hover {
            transform: scale(1.04);
            box-shadow: 0 10px 25px rgba(0,0,0,0.20);
        }

        /* ====== BOUTONS STYLE ====== */
        .train-btn, .daily-btn, .bus-btn, .securite-btn {
            background: #00aee0;
            color: white;
            border-radius: 40px;
            font-weight: bold;
        }

        /* ====== STRUCTURE PAGE ====== */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .page-wrapper {
            flex: 1;
        }
    </style>
</head>

<body>
<?php $this->beginBody(); ?>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg"
     style="background:#d6eaff; position:fixed; top:0; width:100%; z-index:1000;">
    <div class="container d-flex justify-content-between align-items-center py-2">

        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="index.php?r=site/index">
            CERICARNextMove
        </a>

        <!-- Boutons selon page et connexion -->
        <div class="d-flex gap-2 align-items-center">
            <?php
            $currentRoute = Yii::$app->controller->route;
            $isRecherchePage = ($currentRoute === 'recherche/index');
            $isLoggedIn = Yii::$app->session->has('user_id');
            ?>

            <?php if ($isRecherchePage && !$isLoggedIn): ?>
                <!-- Recherche sans connexion -->
                <a href="index.php?r=site/index" class="btn btn-outline-light btn-sm">🏠 Accueil</a>
                <a href="index.php?r=internaute/connexion" class="btn btn-outline-primary btn-sm">Connexion</a>
                <a href="index.php?r=internaute/inscription" class="btn btn-primary btn-sm">Inscription</a>

            <?php elseif (!$isLoggedIn): ?>
                <!-- Non connecté -->
                <a href="index.php?r=internaute/connexion" class="btn btn-sm btn-warning">Connexion</a>
                <a href="index.php?r=internaute/inscription" class="btn btn-sm btn-secondary">Inscription</a>

            <?php else: ?>
                <!-- Connecté -->
                <span class="mx-2">Bonjour, <?= Yii::$app->session->get('user_pseudo') ?></span>
                <a href="index.php?r=voyage/create" class="btn btn-success btn-sm">🚗 Proposer</a>
                <a href="index.php?r=internaute/profile" class="btn btn-info btn-sm">👤 Profil</a>
                <a href="index.php?r=internaute/deconnexion" class="btn btn-danger btn-sm">🚪 Déconnexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Espace sous la navbar -->
<div style="height:80px;"></div>

<!-- ================= CONTENU ================= -->
<div class="page-wrapper">
    <?= $content ?>
</div>

<!-- ================= FOOTER ================= -->
<footer class="text-center py-3"
        style="background:#d6eaff; border-top:1px solid #c2d9f2;">
    <p class="m-0">CERICAR_NextMove © 2025 — Projet Web Yii — Univ Avignon</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php $this->endBody(); ?>

<!-- ================= AJAX NAVIGATION ================= -->
<script>
/* Navigation interne AJAX sans rechargement complet */
(function () {
    function isInternalLink(link) {
        if (!link || !link.href) return false;
        if (link.target && link.target !== '_self') return false;
        return link.href.startsWith(window.location.origin);
    }

    function loadPage(url) {
        fetch(url)
            .then(res => res.text())
            .then(html => {
                let doc = new DOMParser().parseFromString(html, 'text/html');
                document.querySelector('.page-wrapper').innerHTML =
                    doc.querySelector('.page-wrapper').innerHTML;
                document.title = doc.title;
                history.pushState({}, '', url);
            });
    }

    document.addEventListener('click', function (e) {
        let link = e.target.closest('a');
        if (isInternalLink(link)) {
            e.preventDefault();
            loadPage(link.href);
        }
    });

    window.addEventListener('popstate', () => loadPage(location.href));
})();
</script>

</body>
</html>
<?php $this->endPage(); ?>
