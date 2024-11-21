<?php
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page = $_GET['page'] ?? 'home';

$isLoggedIn = isset($_SESSION['LoginId']);

$publicPages = ['home', 'login', 'register'];

if ($isLoggedIn && in_array($page, ['login', 'register'])) {
    header('Location: ?page=home');
    exit();
}

if (!$isLoggedIn && !in_array($page, $publicPages)) {
    header('Location: ?page=login');
    exit();
}

switch ($page) {
    case 'about':
        include './src/app/about/index.php';
        break;
    case 'services':
        include './src/app/services/index.php';
        break;
    case 'contact':
        include './src/app/contact/index.php';
        break;
    case 'dashboard':
        include './src/app/dashboard/index.php';
        break;
    case 'settings':
        include './src/app/settings/index.php';
        break;
    case 'login':
        include './src/app/login/index.php';
        break;
    case 'register':
        include './src/app/register/index.php';
        break;
    default:
        include './src/app/home/index.php';
        break;
}
?>

<?php include './src/components/header/index.php'; ?>
