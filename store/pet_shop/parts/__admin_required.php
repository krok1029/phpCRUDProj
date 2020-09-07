<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['admin1'])) {
    header('Location: login.php');
    exit;
}
