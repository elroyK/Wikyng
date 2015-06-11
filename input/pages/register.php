<?php if (logged_in()) fourohfour();?>
    <form name="inscription" class="customForm smallForm" method="post">
        <label for="uLogin">Nom d'utilisateur :</label>
        <input type="text" name="uLogin" required size="20" maxlength="<?php echo $ini_array['sizes']['login_max'];?>">
        <p>(<?php echo $ini_array['sizes']['login_min'];?>-<?php echo $ini_array['sizes']['login_max'];?> caractères, a-z, A-Z, 0-9)</p><br>
        <label for="uMail">Adresse mail :</label><input type="email" name="uMail" size="20" maxlength="50" required="email"><br>
        <label for="vMail">Confirmer l'adresse mail :</label>
        <input type="email" name="vMail" size="20" maxlength="50" required="email"><br>
        <label for="uPass">Mot de passe :</label><input type="password" required name="uPass" size="20">
        <p>(minimum <?php echo $ini_array['sizes']['pass_min'];?> caractères)</p><br>
        <label for="vPass">Confirmer le mot de passe :</label><input type="password" required name="vPass" size="20"><br>
        <input type="submit" name="register" id="sendButton" value="S'inscrire">
    </form>

    <?php

    if (isset($_POST['register']))
    {
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
        else $mailTaken = FALSE;

        if (!filter_var($_POST['uMail'], FILTER_VALIDATE_EMAIL)){
            wrongEmail();
        }
        else if ($_POST['uMail'] != $_POST['vMail']){
            wrongVMail();
        }
        else if (strlen($_POST['uPass']) < $ini_array['sizes']['pass_min']){
            shortPass();
        }
        else if ($_POST['vPass'] != $_POST['uPass']){
            wrongVPass();
        }
        else if(strlen($_POST['uLogin']) < $ini_array['sizes']['login_min'] || strlen($_POST['uLogin']) > $ini_array['sizes']['login_max']
            || !preg_match("/^[a-zA-Z0-9]*$/",$_POST['uLogin'])){
            wrongULogin();
        }
        else if($loginTaken){
            uLoginTaken();
        }
        else if($mailTaken){
            uMailTaken();
        }
        else if (!empty($_POST['uLogin']) && !empty($_POST['uMail']) &&
             !empty($_POST['vMail']) && !empty($_POST['uPass']) &&
             !empty($_POST['vPass'])) {
            $pass = $_POST['uPass'];
            $pass = safe($pass);
            $pass = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $db0->prepare("INSERT INTO tbuser (userLogin, userPwd, userMail, userDateInscription) VALUES (:uLogin, :uPass, :uMail, :uRegDate)");
            $stmt->bindValue(':uLogin', $username, PDO::PARAM_STR);
            $stmt->bindValue(':uPass', $pass, PDO::PARAM_STR);
            $stmt->bindValue(':uMail', $tryMail, PDO::PARAM_STR);
            $stmt->bindValue(':uRegDate', getDateYMDHIS(), PDO::PARAM_STR);

            $stmt->execute();

            $query_Plvl = $db0->prepare("SELECT userId FROM tbuser WHERE userlogin=:uLogin");
            $query_Plvl->bindValue(':uLogin', $username);
            $query_Plvl->execute();
            $uID = $query_Plvl->fetch();

            $stmt_avtr = $db0->prepare("INSERT INTO user_avatar (user_id, avatar_id, dateC) VALUES (:uID, :aID, :dateC)");
            $stmt_avtr->bindValue(':uID', $uID, PDO::PARAM_INT);
            $stmt_avtr->bindValue(':aID', $ini_array['other']['avatarid_default'], PDO::PARAM_STR);
            $stmt_avtr->bindValue(':dateC', getDateYMD(), PDO::PARAM_STR);
            $stmt_avtr->execute();

            $stmt_prfl = $db0->prepare("INSERT INTO user_profil (user_id, profil_id, dateC) VALUES (:uID, :pID, :dateC)");
            $stmt_prfl->bindValue(':uID', $uID, PDO::PARAM_INT);
            $stmt_prfl->bindValue(':pID', $ini_array['other']['profilid_WFV'], PDO::PARAM_STR);
            $stmt_prfl->bindValue(':dateC', getDateYMD(), PDO::PARAM_STR);
            $stmt_prfl->execute();

            $from = $ini_array['main_infos']['mailto'];
            $subject = $ini_array['main_infos']['title'];
            $subject .= ": Inscription";

            $confHead  = "From: ".$from."\r\n";
            $confHead .= "Reply-To: ".$from."\r\n";
            $confHead .= "MIME-Version: 1.0\r\n";
            $confHead .= "Content-Type: text/html; charset=utf-8\r\n";

            $mailMessage  = "<html><body>";
            $mailMessage .= "<h2>Inscription sur ";
            $mailMessage .= $ini_array['main_infos']['title'];
            $mailMessage .= "</h2><p><b>Nom d'utilisateur : <b>";
            $mailMessage .= $_POST['uLogin'];
            $mailMessage .= "</h2><p><b>Adresse mail : <b>";
            $mailMessage .= $_POST['uMail'];
            $mailMessage .= "</p></body></html>";

            mail($_POST['uMail'], $subject, $mailMessage, $confHead);

            registered();

        } else {
            unfilledFields();
        }
    }
    ?>