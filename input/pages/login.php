    <?php if (logged_in()) fourohfour();?>

    <form name="connexion" class="customForm" id="connectForm" method="post">
        <label for="cLogin">Nom d'utilisateur :</label><input type="text" required name="cLogin" size="20" maxlength="50"><br>
        <label for="cPass">Mot de passe :</label><input type="password" required name="cPass" size="20" maxlength="50"><br>
        <input type="submit" name="log_in" id="sendButton" value="Se connecter">
    </form>

    <?php
    if (isset($_POST['log_in']))
    {
       if (!empty($_POST['cLogin']) && !empty($_POST['cPass'])) {
            $pass = safe($_POST['cPass']);
            $login = safe($_POST['cLogin']);
            $login = strtolower($login);
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $query1 = $db0->prepare("SELECT userMail FROM tbuser WHERE userMail=:login");
                $query1->bindValue(':login', $login, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetch();
                $items_found1 = (!empty($result1));
                if ($items_found1) {
                    $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userMail=:login");
                    $query2->bindValue(':login', $login, PDO::PARAM_STR);
                    $query2->execute();
                    $password = $query2->fetch();
                    if (password_verify($pass, $password[0])) {
                        $query = $db0->prepare("SELECT userId, userLogin, userMail
                                    FROM tbuser
                                    WHERE userMail = :login");
                        $query->bindValue(':login', $login, PDO::PARAM_STR);
                        $query->execute();
                        $sessionData = $query->fetch();
                        $_SESSION['id'] = $sessionData['userId'];
                        $_SESSION['login'] = $sessionData['userLogin'];
                        $_SESSION['mail'] = $sessionData['userMail'];
                        updateLevel($db0);
                        session_write_close();

                        $stmt = $db0->prepare("UPDATE tbuser SET userLastConnected=:uConDate WHERE userId=:uID");
                        $stmt->bindValue(':uConDate', getDateYMDHIS(), PDO::PARAM_STR);
                        $stmt->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt->execute();
                        loginOK();
                    } else {
                        loginFailed();
                        session_write_close();
                    }
                } else {
                    loginFailed();
                    session_write_close();
                }
            } else {
                $query1 = $db0->prepare("SELECT userLogin FROM tbuser WHERE userLogin=:login");
                $query1->bindValue(':login', $login, PDO::PARAM_STR);
                $query1->execute();
                $result1 = $query1->fetch();
                $items_found1 = (!empty($result1));
                if ($items_found1) {
                    $query2 = $db0->prepare("SELECT userPwd FROM tbuser WHERE userLogin=:login");
                    $query2->bindValue(':login', $login, PDO::PARAM_STR);
                    $query2->execute();
                    $password = $query2->fetch();
                    if (password_verify($pass, $password[0])) {
                        $query = $db0->prepare("SELECT userId, userLogin, userMail
                                    FROM tbuser
                                    WHERE userLogin = :login");
                        $query->bindValue(':login', $login, PDO::PARAM_STR);
                        $query->execute();
                        $sessionData = $query->fetch();
                        $_SESSION['id'] = $sessionData['userId'];
                        $_SESSION['login'] = $sessionData['userLogin'];
                        $_SESSION['mail'] = $sessionData['userMail'];
                        updateLevel($db0);
                        session_write_close();

                        $stmt = $db0->prepare("UPDATE tbuser SET userLastConnected=:uConDate WHERE userId=:uID");
                        $stmt->bindValue(':uConDate', getDateYMDHIS(), PDO::PARAM_STR);
                        $stmt->bindValue(':uID', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt->execute();
                        loginOK();
                    } else {
                        loginFailed();
                        session_write_close();
                    }
                } else {
                    loginFailed();
                    session_write_close();
                }
            }
        }
        else {
            loginFailed();
        }
    }
    ?>