<?php

session_start();

// Kung hindi naka-login, ibalik sa login page
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: pages/login.php");
    exit;
}
