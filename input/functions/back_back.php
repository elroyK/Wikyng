<?php
    function updateLevel($db0){
        $queryA = $db0->prepare("SELECT profil_level FROM tbprofil WHERE profil_id=:prof_id");
        $queryB = $db0->prepare("SELECT profil_id FROM user_profil WHERE user_id=:uID");
        $queryB->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
        $queryB->execute();
        $profID = $queryB->fetch();
        $queryA->bindValue(':prof_id', $profID[0], PDO::PARAM_INT);
        $queryA->execute();
        $plevel=$queryA->fetch();
        $_SESSION['level'] = $plevel['profil_level'];
    }

    function getLevel($db0, $id){
        $queryA = $db0->prepare("SELECT profil_level FROM tbprofil WHERE profil_id=:prof_id");
        $queryB = $db0->prepare("SELECT profil_id FROM user_profil WHERE user_id=:uID");
        $queryB->bindValue(':uID', $id, PDO::PARAM_INT);
        $queryB->execute();
        $profID = $queryB->fetch();
        $queryA->bindValue(':prof_id', $profID[0], PDO::PARAM_INT);
        $queryA->execute();
        $plevel=$queryA->fetch();
        return $plevel['profil_level'];
    }

    function getLogin($db0, $id){
        $query = $db0->prepare("SELECT userLogin FROM tbuser WHERE userId=:uid");
        $query->bindValue(':uid', $id, PDO::PARAM_INT);
        $query->execute();
        $login=$query->fetch();
        return $login['userLogin'];
    }

    function getMail($db0, $id){
        $query = $db0->prepare("SELECT userMail FROM tbuser WHERE userId=:uid");
        $query->bindValue(':uid', $id, PDO::PARAM_INT);
        $query->execute();
        $mail=$query->fetch();
        return $mail['userMail'];
    }

    function getAvatar($db0, $uID){
        $queryA = $db0->prepare("SELECT nom FROM tbavatar WHERE aID=:aID");
        $queryB = $db0->prepare("SELECT avatar_id FROM user_avatar WHERE user_id=:uID");
        $queryB->bindValue(':uID', $uID, PDO::PARAM_INT);
        $queryB->execute();
        $aID = $queryB->fetch();
        $queryA->bindValue(':aID', $aID[0], PDO::PARAM_INT);
        $queryA->execute();
        $avatar=$queryA->fetch();
        return $avatar['nom'];
    }

    function killDB($db){
        $db=NULL;
    }

    function safe($it){
        return stripslashes(htmlspecialchars($it));
    }
?>