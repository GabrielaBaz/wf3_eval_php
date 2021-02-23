<?php

include('./inclusions/database.php');

$message = NULL;
$default_photo = 'https://images.unsplash.com/photo-1464082354059-27db6ce50048?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80';

$sql = 'SELECT * FROM logement';
$result = $bdd->query($sql);
$all_loges = $result->fetchAll();

//function to cut the description text if it´s too long
function truncateWords($input, $numwords, $padding = "...")
{
    $output = strtok($input, " \n");
    while (--$numwords > 0) $output .= " " . strtok(" \n");
    if ($output != $input) $output .= $padding;
    return $output;
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include('./inclusions/head.php'); ?>

<body>
    <?php include('./inclusions/header.php'); ?>

    <section class="main-list container py-5 w-100">

        <h1 class="my-5 pt-3 text-center">NOS LOGEMENTS</h1>
        <p>Cliquez sur le titre pour afficher un logement</p>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Ville</th>
                    <th scope="col">CP</th>
                    <th scope="col">Surface</th>
                    <th scope="col">Type</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_loges as $key => $loge) { ?>
                    <tr>
                        <th scope="row"><?= $key + 1 ?></th>
                        <td><a href="display_loge.php?loge_id=<?= $loge['id_logement'] ?>"><?= $loge['titre'] ?></a></td>
                        <td><?= $loge['adresse'] ?></td>
                        <td><?= $loge['ville'] ?></td>
                        <td><?= $loge['cp'] ?></td>
                        <td><?= $loge['surface'] ?></td>
                        <td><?= $loge['type'] ?></td>
                        <td><?= $loge['prix'] ?></td>
                        <td><img class="loge-img" src="<?= $loge['photo'] ?? $default_photo ?>" alt="photologement"></td>
                        <td><?= strlen($loge['description']) > 100 ? truncateWords($loge['description'], 10) : $loge['description'] ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>


    </section>




</body>

</html>