<?php
    if (!logged_in()) fourohfour();
    session_destroy();
    discon();
?>