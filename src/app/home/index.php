<?php
//require 'header.php';
//session_start(); // Uruchomienie sesji
//
//// Sprawdzenie, czy użytkownik jest zalogowany
//if (isset($_SESSION['email'])) {
//    $user_email = $_SESSION['email']; // Pobranie emaila z sesji
//} else {
//    // Jeśli użytkownik nie jest zalogowany, przekierowanie na stronę logowania
//    header("Location: ?page=login");
//    exit();
//}
////?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Strona główna</title>
    <link rel="stylesheet" href="../../../style.css">
</head>
<body>
<div class="container">
    <?php
    include './src/components/navbar/navbar.php';
    ?>
<!--    <h1>Witaj, --><?php //echo htmlspecialchars($user_email); ?><!--!</h1>-->
    <?php
    include './src/app/home/heroComponent/heroComponent.php';
    include './src/app/home/aboutComponent/aboutComponent.php';
    include './src/app/home/featuresComponent/featuresComponent.php';
    include './src/app/home/servicesComponent/servicesComponent.php';
    include './src/components/footer/footer.php';
    ?>
</div>
</body>
</html>
