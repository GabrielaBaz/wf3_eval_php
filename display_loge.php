<?php

include('./inclusions/database.php');

$default_photo = 'https://images.unsplash.com/photo-1464082354059-27db6ce50048?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80';


if (isset($_GET['loge_id']) && $_GET['loge_id'] != '') {

    $sql = 'SELECT * FROM logement WHERE id_logement=:loge_id';
    $result = $bdd->prepare($sql);
    $result->execute(array('loge_id' => $_GET['loge_id']));
    $row = $result->fetch();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include('./inclusions/head.php'); ?>

<body>
    <?php include('./inclusions/header.php'); ?>

    <section class="container py-5 w-75 d-flex justify-content-center">

        <div class="card my-5 w-50">
            <img class="img-display" src="<?= $row['photo'] ?? $default_photo ?>" class="card-img-top" alt="logement">
            <div class="card-body">
                <h2 class="card-title"><?= $row['titre'] ?></h2>
                <p class="card-text">Adresse: <?= $row['adresse'] . ' | ' . $row['ville'] . ' | ' . $row['cp'] ?></p>
                <p class="card-text">Surface: <?= $row['surface'] ?></p>
                <p class="card-text">Type: <?= $row['type'] ?></p>
                <p class="card-text">Prix: <?= $row['prix'] ?></p>
                <p class="card-text">Description: <?= $row['description'] ?></p>
                <a href="list_logement.php" class="btn btn-warning fs-4">Back to list</a>
            </div>
        </div>




    </section>

</body>

</html>