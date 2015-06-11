<?php
    function sub_bullet($db0){
        echo "<img src=\"./img/";
        if (logged_in() && $_SESSION['id'] == 17) echo "G";
        else if (isAdmin($db0)) echo "A";
        else if (isModo($db0)) echo "M";
        else echo "D";
        echo "_sub_bullet.png\" class=\"subbullet\">";
    }

    function fourohfour(){
        echo "<script>window.location.href=\"./index.php?page=404\"</script>";
    }

    function getDateYMD(){
        $uConDate = new DateTime();
        $usableDate = $uConDate->format('Y-m-d');
        return $usableDate;
    }

    function getDateYMDHIS(){
        $uConDate = new DateTime();
        $usableDate = $uConDate->format('Y-m-d H:i:s');
        return $usableDate;
    }

    function getDateFullDigits(){
        $uConDate = new DateTime();
        $usableDate = $uConDate->format('YmdHis');
        return $usableDate;
    }
?>