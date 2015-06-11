<?php
    if (!isAdmin($db0)) fourohfour();
    $id = $_GET['id'];
?>
<div id="adminModUser" class="centerDiv">
    <div id="adminProfileForms">
        <form name="password" class="customForm smallForm" id="adminModPwd" method="post">
            <fieldset>
                <legend>Modification du mot de passe de l'utilisateur</legend>
                <label for="aPass">Mot de passe administrateur :</label><input type="password" required name="aPass" size="20"><br>
                <label for="nPass">Nouveau mot de passe :</label><input type="password" required name="nPass" size="20">
                <p>(minimum <?php echo $ini_array['sizes']['pass_min'];?> caractères)</p><br>
                <label for="vPass">Confirmer le mot de passe :</label><input type="password" required name="vPass" size="20"><br>
            </fieldset>
            <input type="submit" name="modPwd" value="Confirmer" id="sendButton">
        </form>

        <form name="profile" class="customForm smallForm" id="profileForm" method="post">
            <fieldset
                ><legend>Entrez votre mot de passe administrateur pour valider les modifications.</legend>
                <label for="uLogin">Nom d'utilisateur :</label>
                <input type="text" required name="uLogin" size="20" maxlength="20" value="<?php echo(getLogin($db0, $id));?>"><br>
                <label for="uMail">Adresse mail :</label>
                <input type="email" required="email" name="uMail" size="20" maxlength="50" value="<?php echo(getMail($db0,$id));?>"><br>
                <label for="vMail">Confirmer l'adresse mail :</label>
                <input type="email" required="email" name="vMail" size="20" maxlength="50" value="<?php echo(getMail($db0, $id));?>">
                <br><label for="aPass">Mot de passe :</label><input type="password" required name="aPass" size="20"><br>
            </fieldset>
            <input type="submit" name="applyMod" id="sendButton" value="Appliquer les modifications">
        </form>
    </div>

    <div id="profileAvatar">
        <h2>Avatar</h2>
        <img src="./img/avatars/<?php echo(getAvatar($db0,$id)); ?>" alt="Avatar" id="proAvatar">
        <form enctype="multipart/form-data" method="post" action="index.php?page=uploadAvatar&modifAdmin=
        <?php echo($id); ?>">
            <label for="avatarFile">Fichier</label><br>
            <input type="file" required name="avatarFile" accept="image/*"><br>
            <input type="submit" value="Upload"></form>
    </div>
</div>

<?php
    if(isset($_POST['modPwd'])){
        if (empty($_POST['nPass']) || empty($_POST['vPass']) || empty($_POST['aPass'])){
            unfilledFields();
        } else if ($_POST['nPass'] != $_POST['vPass']){
            wrongVPass();
        } else {
            $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userId=:uID");
            $query2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
            $query2->execute();
            $password = $query2->fetch();
            if ($_POST['aPass'] == $password[0]) {
                $stmt = $db0->prepare("UPDATE tbuser SET userPwd = :nPwd WHERE userId = :uID");
                $pwd = safe($_POST['nPass']);
                $stmt->bindValue(':nPwd', $pwd, PDO::PARAM_STR);
                $stmt->bindValue(':uID', $id, PDO::PARAM_INT);
                $stmt->execute();
                $from = $ini_array['main_infos']['mailto'];
                $subject = $ini_array['main_infos']['title'];
                $subject .= " - Modification du profil par l'administrateur";

                $confHead  = "From: ".$from."\r\n";
                $confHead .= "Reply-To: ".$from."\r\n";
                $confHead .= "MIME-Version: 1.0\r\n";
                $confHead .= "Content-Type: text/html; charset=utf-8\r\n";

                $mailMessage  = "<html><body>";
                $mailMessage .= "<h2>L'administrateur de ";
                $mailMessage .= $ini_array['main_infos']['title'];
                $mailMessage .= "a modifié votre profil.</h2>
                                <p>Les champs suivant ont été modifiés :</p>
                                <ul>";
                $mailMessage .= "<li>Mot de passe</li>";
                $mailMessage .= "</ul>";

                mail($_POST['uMail'], $subject, $mailMessage, $confHead);
                passModified();
            } else wrongPass();
        }
    }
    if (isset($_POST['modProf'])) {
        echo "<script>window.location.href=\"./index.php?page=profile&modify=1\"</script>";
    }
    if (isset($_POST['applyMod'])){

        $modifications = array();

        if (empty($_POST['aPass'])) enterPass();
        else if(empty($_POST['uLogin']) || empty($_POST['uMail'])
            || empty($_POST['vMail']) || empty($_POST['vMail'])) unfilledFields();
        $pass = $_POST['aPass'];
        if ($_POST['uLogin'] != getLogin($db0, $id)) {
            $username = $_POST['uLogin'];
            $username = safe($username);
            $usernameLC = strtolower($username);
            $query1 = $db0->prepare("SELECT userLogin FROM tbuser WHERE userLogin=:login");
            $query1->bindValue(":login", $usernameLC, PDO::PARAM_STR);
            $query1->execute();
            $result1 = $query1->fetch();
            $items_found1 = (!empty($result1));
            if($items_found1>0)
            {
                $loginTaken = TRUE;
            }
            else $loginTaken = FALSE;
            if(strlen($_POST['uLogin']) < $ini_array['sizes']['login_min']
                || strlen($_POST['uLogin']) > $ini_array['sizes']['login_max']
                || !preg_match("/^[a-zA-Z0-9]*$/",$_POST['uLogin'])){
                wrongULogin();
            }
            else if($loginTaken){
                uLoginTaken();
            }
            else {
                $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userId=:uID");
                $query2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                $query2->execute();
                $password = $query2->fetch();
                if ($pass == $password[0]) {
                    $stmt1 = $db0->prepare("UPDATE tbuser SET userLogin = :uLogin WHERE userId=:uID");
                    $stmt1->bindValue(':uID', $id, PDO::PARAM_INT);
                    $stmt1->bindValue(':uLogin', $username, PDO::PARAM_STR);
                    $stmt1->execute();
                    $didModif = true;
                    $modifications[] = "Nom d'utilisateur";
                } else wrongPass();
            }
        }
        if ($_POST['uMail']!= $_SESSION['mail']) {

            $tryMail = strtolower($_POST['uMail']);
            $tryMail = safe($tryMail);
            $query2 = $db0->prepare("SELECT userMail FROM tbuser WHERE userMail=:tryMail;");
            $query2->bindValue(':tryMail', $tryMail, PDO::PARAM_STR);
            $query2->execute();
            $result2 = $query2->fetch();
            $items_found2 = (!empty($result2));
            if($items_found2>0)
            {
                $mailTaken = TRUE;
            }
            if($mailTaken)
                uMailTaken();
            else if (!filter_var($_POST['uMail'], FILTER_VALIDATE_EMAIL)){
                wrongEmail();
            }
            else if ($_POST['uMail'] != $_POST['vMail']){
                wrongVMail();
            }
            else {
                $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userId=:uID");
                $query2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                $query2->execute();
                $password = $query2->fetch();
                if ($pass == $password[0]) {
                    $stmt2 = $db0->prepare("UPDATE tbuser SET userMail = :uMail WHERE userId=:uID");
                    $stmt2->bindValue(':uID', $id, PDO::PARAM_INT);
                    $stmt2->bindValue(':uMail', $tryMail, PDO::PARAM_STR);
                    $stmt2->execute();
                    $didModif = true;
                    $modifications[] = "Adresse email";
                } else wrongPass();
            }
        }
        if (!empty($didModif)) {
            $from = $ini_array['main_infos']['mailto'];
            $subject = $ini_array['main_infos']['title'];
            $subject .= " - Modification du profil par l'administrateur";

            $confHead  = "From: ".$from."\r\n";
            $confHead .= "Reply-To: ".$from."\r\n";
            $confHead .= "MIME-Version: 1.0\r\n";
            $confHead .= "Content-Type: text/html; charset=utf-8\r\n";

            $mailMessage  = "<html><body>";
            $mailMessage .= "<h2>L'administrateur de ";
            $mailMessage .= $ini_array['main_infos']['title'];
            $mailMessage .= "a modifié votre profil.</h2>
                            <p>Les champs suivant ont été modifiés :</p>
                            <ul>";
            foreach($modifications as $value){
                $mailMessage .= "<li>";
                $mailMessage .= $value;
                $mailMessage .= "</li>";
            }
            $mailMessage .= "</ul>";

            mail($_POST['uMail'], $subject, $mailMessage, $confHead);
            modifDone();
        }
    }
?>

