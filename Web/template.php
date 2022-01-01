<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/public/style/style.css" rel="stylesheet">
    <link href="/public/style/template/header.css" rel="stylesheet">
    <link href="/public/style/template/footer.css" rel="stylesheet">
    <script src="/libraries/jquery-3.6.0/jquery-3.6.0.min.js"></script>

    <!-- View styles -->
    <?php if(isset($styles)) : ?>
        <?php foreach ($styles as &$style): ?>
            <link href="<?=$style?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- View scripts -->
    <?php if(isset($scripts)) : ?>
        <?php foreach ($scripts as &$script): ?>
            <script src="<?=$script?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- View title -->
    <title><?= $title ?></title>
</head>

<body class="flex-column">
    <header>
            <nav class="flex-row">
                <a id="nav-home" href="/index.php">
                    <img src="/public/images/home.png" alt="Home button">
                </a>
                <p id="nav-title">Interface de gestion des bons de commande et de livraison</p>
            </nav>
    </header>

    <?= $content ?>

    <footer class="flex-row">
        <p>&copy; 2021 PROTOTYPE - PROJET DE DIGITALISATION</p>
    </footer>
</body>
</html>