
<?php
// Pobranie ścieżki strony z URL, np. ?page=about
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Obsługa routingu dla podstron
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
    default:
        include './src/app/home/index.php'; // domyślna strona główna
        break;
}
?>
<?php include './src/components/header/header.php'; ?>

<!--<div class="container">-->
<!---->
<!--</div>-->

