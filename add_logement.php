<?php

include('./inclusions/database.php');
$message = NULL;
$messagecp = NULL;
$img_path_name = NULL;
const IMAGES_FOLDER = './images/';

//Check mandatory fields first
if (
    isset($_POST['titre']) && isset($_POST['adresse']) && isset($_POST['ville']) && isset($_POST['cp']) && isset($_POST['surface']) && isset($_POST['type'])
    && isset($_POST['prix'])
) {

    if ($_POST['titre'] != '' && $_POST['adresse'] != '' && $_POST['ville'] != '' && $_POST['cp'] != '' && $_POST['surface'] != '' && $_POST['type'] != '' && $_POST['prix'] != '') {

        $loge_titre = htmlspecialchars($_POST['titre']);
        $loge_adresse = htmlspecialchars($_POST['adresse']);
        $loge_ville = htmlspecialchars($_POST['ville']);
        $loge_cp = NULL;
        $loge_surface = (int) htmlspecialchars($_POST['surface']);
        $loge_type = htmlspecialchars($_POST['type']);
        $loge_prix = (int) htmlspecialchars($_POST['prix']);

        //check description, if empty store as null
        if (isset($_POST['description']) && $_POST['description'] != '') {
            $loge_desc = htmlspecialchars($_POST['description']);
        } else {
            $loge_desc = NULL;
        }

        //check postal code
        if (preg_match('#^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$#', $_POST['cp'])) {
            $loge_cp = htmlspecialchars($_POST['cp']);
        } else {
            $messagecp = '<div class="alert alert-danger" role="alert">Check!</div>';
        }


        //upload the photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            //echo "here here 1";
            if ($_FILES['photo']['size'] <= 10000000) {
                //echo "here here 2";
                $informationsImage = pathinfo($_FILES['photo']['name']);
                $extensionImage = $informationsImage['extension'];
                $extensionsArray = array('png', 'gif', 'jpg', 'jpeg', 'jfif');

                if (in_array($extensionImage, $extensionsArray)) {
                    //echo "here here 3";
                    $img_name = IMAGES_FOLDER . 'logement' . '_' . time() . '.' . $extensionImage;

                    //if images folder does not exist, create it
                    if (!is_dir('images')) {
                        mkdir('images', 0755, true); //0755 is recommended for security purposes
                    }

                    move_uploaded_file($_FILES['photo']['tmp_name'], $img_name);
                    $img_path_name = $img_name; //to store in DB
                }
            }
        } else {
            $message = '<div class="alert alert-danger" role="alert">Image error.</div>';
        }
        //end of photo upload

        //store in database
        $sql = 'INSERT INTO logement(titre, adresse, ville, cp, surface, type, prix, photo, description) 
    VALUES (:titre, :adresse, :ville, :cp, :surface, :type, :prix,:photo, :description)';
        $result = $bdd->prepare($sql);
        if ($result->execute(array(
            'titre' => $loge_titre,
            'adresse' => $loge_adresse,
            'ville' => $loge_ville,
            'cp' => $loge_cp,
            'surface' => $loge_surface,
            'type' => $loge_type,
            'prix' => $loge_prix,
            'photo' => $img_path_name,
            'description' => $loge_desc,

        ))) {
            header('Location:add_logement.php?add=1');
        } else {
            $message = '<div class="alert alert-light" role="alert">Logement non ajouté.</div>';
        };
    } else {
        $message = '<div class="alert alert-light" role="alert">Some mandatory fields are empty.</div>';
    }
}

if (isset($_GET['add'])) {

    switch ($_GET['add']) {
        case '1':
            $message = '<div class="alert alert-light" role="alert">Logement ajouté!</div>';
            break;
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<?php include('./inclusions/head.php'); ?>

<body>

    <?php include('./inclusions/header.php'); ?>

    <section class="container py-5 w-50">

        <p><?= $message ?? '' ?></p>
        <h1 class="my-5 pt-3 text-center">AJOUTER UN LOGEMENT</h1>

        <form action="add_logement.php" method="post" enctype="multipart/form-data">

            <div class="input-group my-4">
                <span class="input-group-text fs-4">Titre</span>
                <input type="text" class="form-control fs-4" name="titre" value="<?= $_POST['titre'] ?? '' ?>">
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text fs-4">Adresse</span>
                <input type="text" class="form-control fs-4" name="adresse" value="<?= $_POST['adresse'] ?? '' ?>">
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text fs-4">Ville</span>
                <input type="text" class="form-control fs-4" name="ville" value="<?= $_POST['ville'] ?? '' ?>">
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text fs-4">Code Postal</span>
                <input type="text" class="form-control fs-4" name="cp" value="<?= $_POST['cp'] ?? '' ?>">
                <span class="petit-message"><?= $messagecp ?? '' ?></span>
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text fs-4">Surface</span>
                <input type="text" class="form-control fs-4" name="surface" value="<?= $_POST['surface'] ?? '' ?>">
            </div>

            <div class="input-group mb-4">
                <label class="input-group-text fs-4">Type</label>
                <select class="form-select fs-4" id="type" name="type">
                    <option selected value="<?= $_POST['type'] ?? '' ?>"><?= $_POST['type'] ?? 'Choose...' ?></option>
                    <option value="Location">Location</option>
                    <option value="Vente">Vente</option>
                </select>
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text fs-4">Prix</span>
                <input type="text" class="form-control fs-4" name="prix" value="<?= $_POST['prix'] ?? '' ?>">
            </div>

            <div class="mb-4">
                <label for="formFile" class="form-label">Photo</label>
                <input class="form-control fs-4" type="file" id="formFile" name="photo">
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text fs-4">Description</span>
                <textarea class="form-control fs-4" name="description" value="<?= $_POST['description'] ?? '' ?>"></textarea>
            </div>

            <div class="input-group mb-4">
                <button class="btn btn-outline-secondary fs-3 w-100" type="submit" id="button-addon1">Add</button>
            </div>

        </form>


    </section>


</body>

</html>