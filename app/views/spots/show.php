<?php require_once '../app/views/inc/header.php'; ?>

<h2><?= $data['spot']['nom'] ?></h2>
<p><?= $data['spot']['description'] ?></p>
<ul>
    <li>😌 Confort : <?= $data['spot']['confort'] ?>/10</li>
</ul>

<a href="<?= URLROOT ?>/spots">← Retour à la liste</a>

<?php require_once '../app/views/inc/footer.php'; ?>
