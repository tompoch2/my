<?php

/**
 * @author ReneXXX
 * @copyright 2016
 */
    session_start();
    session_destroy();
    unset($_SESSION['username']);
    header('Location: index.php');
?>