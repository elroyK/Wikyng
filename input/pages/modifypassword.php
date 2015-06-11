<?php
    if(!logged_in()) fourohfour();
?>

<div id="profilepage">
    <form name="password" class="customForm smallForm" id="profileForm" method="post">
        <fieldset><legend>Modification du mot de passe</legend>
        <label for="uPass">Mot de passe actuel :</label><input type="password" name="uPass" size="20"><br>
        <label for="nPass">Nouveau mot de passe :</label><input type="password" name="nPass" size="20">
        <p>(minimum <?php echo $ini_array['sizes']['pass_min'];?> caractères)</p><br>
        <label for="vPass">Confirmer le mot de passe :</label><input type="password" name="vPass" size="20"><br></fieldset>
        <input type="submit" name="modPwd" value="Confirmer" id="sendButton">
    </form>
</div>

<?php
    if(isset($_POST['modPwd'])){
        if (empty($_POST['nPass']) || empty($_POST['vPass']) || empty($_POST['uPass'])){
            unfilledFields();
        } else if ($_POST['nPass'] != $_POST['vPass']){
            wrongVPass();
        } else {
            $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userId=:uID");
            $query2->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
            $query2->execute();
            $password = $query2->fetch();
            if ($_POST['uPass'] == $password[0]) {
                $stmt = $db0->prepare("UPDATE tbuser SET userPwd = :nPwd WHERE userId = :uID");
                $pwd = safe($_POST['nPass']);
                $stmt->bindValue(':nPwd', $pwd, PDO::PARAM_STR);
                $stmt->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                $stmt->execute();
                passModified();
            } else wrongPass();
        }
    }
?>