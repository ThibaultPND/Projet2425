<?php require_once '../app/views/inc/header.php'; ?>

<h2>Les meilleurs spots pour dormir à l'école</h2>

<ul>
<?php foreach($data['spots'] as $spot): ?>
    <li>
        <h3><a href="<?= URLROOT ?>/spots/show/<?= $spot['id'] ?>"><?= $spot['nom'] ?></a></h3>
        <p><?= $spot['description'] ?></p>
        <p>😌 Confort : <?= $spot['confort'] ?>/10 | 🔇 Silence : <?= $spot['silence'] ?>/10 | 🚨 Risque : <?= $spot['risque'] ?>/10</p>
    </li>
<?php endforeach; ?>
</ul>

<?php require_once '../app/views/inc/footer.php'; ?>
