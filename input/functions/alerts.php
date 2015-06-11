<?php
    function unfilledFields() {
        echo "<script>
        alert(\"Veuillez remplir tous les champs.\");
        </script>";
    }

    function invalidMail() {
        echo "<script>
        alert(\"Addresse invalide\");
        </script>";
    }

    function contactSent() {
        echo "<script>
        window.location.href=\"./index.php\";
        alert(\"Contact envoyé !\");
        </script>";
    }

    function wrongEmail() {
        echo "<script>
        alert(\"Adresse invalide.\")
        </script>";
    }

    function wrongVMail() {
        echo "<script>
        alert(\"Les adresses ne correspondent pas.\")
        </script>";
    }

    function wrongVPass() {
        echo "<script>
        alert(\"Les mots de passe ne correspondent pas.\")
        </script>";
    }

    function wrongPass() {
        echo "<script>
        alert(\"Mot de passe incorrect.\")
        </script>";
    }

    function shortPass() {
        echo "<script>
        alert(\"Mot de passe trop court.\")
        </script>";
    }

    function wrongULogin() {
        echo "<script>
        alert(\"Nom d\'utilisateur invalide.\")
        </script>";
    }

    function uLoginTaken() {
        echo "<script>
        alert(\"Nom d\'utilisateur déjà utilisé.\")
        </script>";
    }

    function uMailTaken() {
        echo "<script>
        alert(\"Vous avez déjà enregistré un compte avec cette adresse.\")
        </script>";
    }

    function registered() {
        echo "<script>
        window.location.href=\"./index.php\";
        alert(\"Inscription réussie !\");
        </script>";
    }

    function loginOK() {
        echo "<script>
        window.location.href=\"./index.php\";
        alert(\"Connexion réussie !\");
        </script>";
    }

    function discon() {
        echo "<script>
        window.location.href=\"./index.php\";
        alert(\"Vous êtes déconnecté !\");
        </script>";
    }

    function modifDone() {
        echo "<script>
        window.location.href=\"./index.php?page=profile\";
        alert(\"Votre profil a été modifié !\");
        </script>";
    }

    function avatarUploaded() {
        echo "<script>
        window.location.href=\"./index.php?page=profile\";
        alert(\"Votre avatar a été modifié !\");
        </script>";
    }

    function loginFailed() {
        echo "<script>
        alert(\"Login ou mot de passe incorrect !\");
        </script>";
    }

    function enterPass() {
        echo "<script>
        alert(\"Veuillez entrer votre mot de passe\");
        </script>";
    }

    function noImageToUpload() {
        echo "<script>
        window.location.href=\"./index.php?page=profile&updateAvatar=1\"
        alert(\"Vous devez choisir un fichier image.\")
        </script>";
    }

    function passModified() {
        echo "<script>
        window.location.href=\"./index.php?page=profile\"
        alert(\"Votre mot de passe a été modifié.\")
        </script>";
    }
?>