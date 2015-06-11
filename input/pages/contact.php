<?php
    if (!empty($_GET['mID'])) {
        $query = $db0->prepare("SELECT * FROM tbmessages WHERE mesId=:mID");
        $query->bindValue(':mID', $_GET['mID'], PDO::PARAM_INT);
        $query->execute();
        if (!($displayedMail = $query->fetch())) $_GET['mID'] = false;
    } else $_GET['mID'] = false;

    if (empty($_GET['uID'])){
        if (!empty($_GET['pID']) && $_GET['uID']==0) $reponse = true;
        else $reponse=false;
    } else $reponse = true;

    if ($reponse) {

        if ($_GET['uID'] == 0){
            $parentIsUser = false;
            $query = $db0->prepare("SELECT mesEmail FROM tbmessages WHERE mesParentId=:pID");
            $query->bindValue(':pID', $_GET['pID'], PDO::PARAM_INT);
            $query->execute();
            if (!($queryMail = $query->fetch())) {
                $hasParent = false;
            } else {
                $targetMail = $queryMail['mesEmail'];
            }
        }
        else {
            $query = $db0->prepare("SELECT userMail FROM tbuser WHERE userId=:uID");
            $query->bindValue(':uID', $_GET['uID'], PDO::PARAM_INT);
            $query->execute();
            if (!($queryMail = $query->fetch())) $parentIsUser = false;
            else {
                $parentIsUser = true;
                $targetMail = $queryMail['userMail'];
            }
        }
        $query = $db0->prepare("SELECT mesSujet FROM tbmessages WHERE mesId=:pID");
        $query->bindValue(':pID', $_GET['pID'], PDO::PARAM_INT);
        $query->execute();
        $parentMail = $query->fetch();
        if (empty($parentMail)) {
            $hasParent = false;
            $test = "Ligne 38";
            $parentIsUser = false;
        }
        else {
            $parentSubject = $parentMail['mesSujet'];
            $hasParent = true;
        }
    } else {
        $test = "Ligne 45";
        $hasParent = false;
        $parentIsUser = false;
    }

?>

    <div id="contact">
    <h2>Contactez-moi !</h2>

    <p>En cas de besoin, vous pouvez me contacter via le formulaire suivant et je vous répondrai dans les plus brefs délais.<br>
        </p>
    <form name="contact" class="customForm" method="post">
        <label for="sujet">Sujet :</label><input type="text" required name="sujet" size="50" maxlength="50"
            <?php if (!empty($_GET['mID'])) echo 'value="'.$displayedMail['mesSujet'].'"';
                  else if ($hasParent)
                        echo "value=\"RE :".$parentSubject."\"";?>><br/>
        <label for="email">Mail :</label><input type="email" required="email" name="email" size="50" maxlength="50"
            <?php
                if (!empty($_GET['mID'])    ) echo 'value="'.$displayedMail['mesEmail'].'"';
                else if (logged_in()) echo "value=\"".$_SESSION['mail']."\"";
                else echo "value=\"echec\"";?>><br/>
        <label for="msg">Message :</label><textarea required rows="5" cols="50" name="msg"><?php if (!empty($_GET['mID'])) echo($displayedMail['mesTexte']); ?></textarea><br/>
        <?php if (empty($_GET['mID'])) echo "<input type=\"submit\" name=\"sendContact\" id=\"sendButton\" value=\"Envoyer\">";
            else echo "<a href=\"index.php?page=contact&uID="
                . $array['userId'] . "&pID=". $array['mesId'] . "\">Répondre</a>"?>
    </form>
    </div>

    <?php

    if (isset($_POST['sendContact']))
    {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            wrongEmail();
        }
        else if (!empty($_POST['sujet']) && !empty($_POST['email']) && !empty($_POST['msg'])) {

            $sujet   = safe($_POST['sujet']);
            $contactMessage = safe($_POST['msg']);

            if (logged_in()) {
                $id = $_SESSION['id'];
                $email = $_SESSION['mail'];
            } else {
                $id = 0;
                $email   = safe($_POST['email']);
            }

            if (empty($_GET['pID'])) $parentID = 0;
            else $parentID = $_GET['pID'];

            $stmt = $db0->prepare("INSERT INTO tbmessages (mesSujet, mesEmail, mesTexte, userId, mesParentId, mesDate, answered)
                                    VALUES (:sujet, :email, :texte, :uID, :pID, :cDate, :ans)");
            $stmt->bindValue(':sujet', $sujet, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':texte', $contactMessage, PDO::PARAM_STR);
            $stmt->bindValue(':uID', $id, PDO::PARAM_INT);
            $stmt->bindValue(':pID', $parentID, PDO::PARAM_INT);
            $stmt->bindValue(':cDate', getDateYMDHIS(), PDO::PARAM_STR);
            $ans = $reponse ? 1 : 0;
            $stmt->bindValue(':ans', $ans, PDO::PARAM_INT);

            $stmt->execute();
            
            if ($hasParent){
                $updateParent = $db0->prepare("UPDATE tbmessages SET answered=1 WHERE mesId = :mID");
                $updateParent->bindValue(':mID', $_GET['pID'], PDO::PARAM_INT);
                $updateParent->execute();

                $contactHead = "From: " . $email . "\r\n";
                $contactHead .= "Reply-To: " . $email . "\r\n";
                $contactHead .= "MIME-Version: 1.0\r\n";
                $contactHead .= "Content-Type: text/html; charset=utf-8\r\n";

                $answerMessage = "Réponse à votre message \"";
                $answerMessage .= $parentSubject;
                $answerMessage .= "\" par l'administrateur de ";
                $answerMessage .= $ini_array['main_infos']['title'];
                $answerMessage .= "<br>Sujet : ";
                $answerMessage .= $sujet;
                $answerMessage .= "<br>Message : <br></p><p>";
                $answerMessage .= $contactMessage;

                mail($targetMail, $sujet, $answerMessage, $contactHead);
            }
            else {
                $from = $ini_array['main_infos']['mailto'];
                $subject = $ini_array['main_infos']['title'];
                $subject .= ": Confirmation d'envoi";

                $confHead = "From: " . $from . "\r\n";
                $confHead .= "Reply-To: " . $from . "\r\n";
                $confHead .= "MIME-Version: 1.0\r\n";
                $confHead .= "Content-Type: text/html; charset=utf-8\r\n";

                $mailMessage = "<html><body>";
                $mailMessage .= "<p>Message envoyé :<br>From : ";
                $mailMessage .= $email;
                $mailMessage .= "<br>Sujet : ";
                $mailMessage .= $sujet;
                $mailMessage .= "<br>Message : <br></p><p>";
                $mailMessage .= $contactMessage;
                $mailMessage .= "</p><p>Merci de l'attention que vous portez à mon site. Je vous répondrai dans les plus brefs délais.<br><br>";
                $mailMessage .= $ini_array['main_infos']['author'];
                $mailMessage .= "</p></body></html>";

                mail($email, $subject, $mailMessage, $confHead);

                $contactHead = "From: " . $email . "\r\n";
                $contactHead .= "Reply-To: " . $email . "\r\n";
                $contactHead .= "MIME-Version: 1.0\r\n";
                $contactHead .= "Content-Type: text/html; charset=utf-8\r\n";

                $sentMessage = "Message envoyé depuis le formulaire de contact du site ";
                $sentMessage .= $ini_array['main_infos']['title'];
                $sentMessage .= "<br><br>";
                $sentMessage .= $contactMessage;

                mail($ini_array['main_infos']['mailto'], $sujet, $sentMessage, $contactHead);
            }
            contactSent();
        } else {
            unfilledFields();
        }
    }
    ?>


