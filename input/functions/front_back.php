<?php
    function logged_in(){
        if (!empty($_SESSION['id']) && $_SESSION['id'] > 0 && !empty($_SESSION['login']))
            return true;
        return false;
    }

    function isGod(){
        if ((logged_in() && $_SESSION['id'] == 17)) return true;
    }

    function isAdmin($db0){
        if (isGod()) return true;
        if ((logged_in() && !empty($_SESSION['level'])))
           return getAdmin($db0, $_SESSION['id']);
        return false;
    }

    function isModo($db0){
        if ((logged_in() && !empty($_SESSION['level'])))
            return getModo($db0, $_SESSION['id']);
        return false;
    }

    function getAdmin($db0, $id){
        if (getLevel($db0, $id) == 1) return true;
        return false;
    }

    function getModo($db0, $id){
        if (getLevel($db0, $id) == 2) return true;
        return false;
    }

    function getUser($db0, $id){
        if (getLevel($db0, $id) == 3) return true;
        return false;
    }

    function getWFV($db0, $id){
        if (getLevel($db0, $id) == 10) return true;
        return false;
    }

    function getWFR($db0, $id){
        if (getLevel($db0, $id) == 11) return true;
        return false;
    }

    function getWFP($db0, $id){
        if (getLevel($db0, $id) == 12) return true;
        return false;
    }

    function getFrozen($db0, $id){
        if (getLevel($db0, $id) == 20) return true;
        return false;
    }

    function getUnreg($db0, $id){
        if (getLevel($db0, $id) == 21) return true;
        return false;
    }

    function getBanned($db0, $id){
        if (getLevel($db0, $id) == 22) return true;
        return false;
    }
?>