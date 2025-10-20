<?php require APPROOT . '/views/inc/header.php'; 

$filename = URLROOT . "/private_assets/plans/plan_rdc.jpg";
$mime = mime_content_type($filename);

header('Content-Type: $mime');
header('Content-Lengh: ' . filesize($filename));
?>

<h1>Plans du bâtiment</h1>

<div class="plans-container">
    <div class="plan">
        <h2>Rez-de-chaussée</h2>
	<img src="<?php readfile($filename); ?>" alt="Plan RDC">
    </div>

    <div class="plan">
        <h2>Premier étage</h2>
        <img src="<?php echo URLROOT; ?>/assets/plans/plan_rp1.jpg" alt="Plan R+1">
    </div>

    <div class="plan">
        <h2>Deuxième étage</h2>
        <img src="<?php echo URLROOT; ?>/assets/plans/plan_rp2.jpg" alt="Plan R+2">
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>

