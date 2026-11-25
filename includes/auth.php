<?php

session_start();

// Redirect unauthenticated users to the login page.
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: pages/login.php");
    exit;
}
