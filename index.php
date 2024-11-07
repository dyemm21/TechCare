
<?php
$page = $_GET['page'] ?? 'home';

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
<?php include './src/components/header/header.php'; ?>

<!--<div class="container">-->
<!---->
<!--</div>-->

