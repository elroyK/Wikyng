<?php //Copyright Zvonko Biškup (http://www.codeforest.net/upload-crop-and-resize-images-with-php)
      //Modifications par Christophe Leroy

    if(empty($_GET['modifAdmin'])) $id = $_SESSION['id'];
    else $id = $_GET['modifAdmin'];

    if (empty($_FILES) || !logged_in()) fourohfour();

    require_once('input/functions/ImageManipulator.php');

    if ($_FILES['avatarFile']['error'] > 0) {
        noImageToUpload();
    } else {
        $okayExtensions = array('.jpg', '.jpeg', '.gif', '.png');
        $fileExtension = strrchr($_FILES['avatarFile']['name'], ".");
        if (in_array($fileExtension, $okayExtensions)) {
            $newName = getDateFullDigits() . '_' . $_FILES['avatarFile']['name'];
            $newName = safe($newName);
            $destination = './img/avatars/' . $newName;

            if (move_uploaded_file($_FILES['avatarFile']['tmp_name'], $destination)) {
                $imagesize = getimagesize($destination);
                $width = $imagesize[0];
                $height = $imagesize[1];

                if ($width > $height && $width > $ini_array['sizes']['avatar_max']) {
                    $shrinkFactor = $width / $ini_array['sizes']['avatar_max'];
                    $height = $height / $shrinkFactor;
                    $manipulation = new ImageManipulator($destination);
                    $image2upload = $manipulation->resample($ini_array['sizes']['avatar_max'], $height);
                    $manipulation->save($destination);
                } else if ($height > $width && $height > $ini_array['sizes']['avatar_max']) {
                    $shrinkFactor = $height / $ini_array['sizes']['avatar_max'];
                    $width = $height / $shrinkFactor;
                    $manipulation = new ImageManipulator($destination);
                    $image2upload = $manipulation->resample($width, $ini_array['sizes']['avatar_max']);
                    $manipulation->save($destination);
                } else if ($height == $width && $height > $ini_array['sizes']['avatar_max']) {
                    $shrinkFactor = $width / $ini_array['sizes']['avatar_max'];
                    $width = $width / $shrinkFactor;
                    $height = $height / $shrinkFactor;
                    $manipulation = new ImageManipulator($destination);
                    $image2upload = $manipulation->resample($ini_array['sizes']['avatar_max'], $ini_array['sizes']['avatar_max']);
                    $manipulation->save($destination);
                }

                $stmt_tbA = $db0->prepare("INSERT INTO tbavatar (nom) VALUES (:nom)");
                $stmt_tbA->bindValue(':nom', $newName, PDO::PARAM_STR);
                $stmt_tbA->execute();

                $query_tbA = $db0->prepare("SELECT aID FROM tbavatar WHERE nom=:nom");
                $query_tbA->bindValue(':nom', $newName, PDO::PARAM_STR);
                $query_tbA->execute();
                $aID = $query_tbA->fetch();

                $stmtU_A = $db0->prepare("UPDATE user_avatar SET avatar_id=:aID, dateC=:dateC WHERE user_id=:uID");
                $stmtU_A->bindValue(':aID', $aID[0], PDO::PARAM_INT);
                $stmtU_A->bindValue(':dateC', getDateYMD(), PDO::PARAM_STR);
                $stmtU_A->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                $stmtU_A->execute();

                avatarUploaded();
            } else noImageToUpload();
        }
    }
?>