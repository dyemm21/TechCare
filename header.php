<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['LoginId'])) {
    header("Location: ?page=login");
    exit();
}

?>

