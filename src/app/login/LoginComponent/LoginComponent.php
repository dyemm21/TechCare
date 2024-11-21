<?php
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
    die("Błąd: brak zmiennej \$conn. Upewnij się, że db.php jest poprawnie zaimportowany.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Logowanie WHERE Email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Haslo'])) {
        $_SESSION['LoginId'] = $user['Id_Logowania'];
        $_SESSION['email'] = $user['Email'];
        $Id_Logowania = $user['Id_Logowania'];

        $stmt = $conn->prepare("SELECT Id_Klienta, Imie, Nazwisko, Id_Kontaktu, Id_Adresu, Zdjecie FROM Klienci WHERE Id_Logowania = :id_logowania");
        $stmt->bindParam(':id_logowania', $Id_Logowania, PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            $_SESSION['ClientId'] = $client['Id_Klienta'];
            $_SESSION['firstname'] = $client['Imie'];
            $_SESSION['lastname'] = $client['Nazwisko'];
            $_SESSION['ContactId'] = $client['Id_Kontaktu'];
            $_SESSION['AddressId'] = $client['Id_Adresu'];
            $_SESSION['photo'] = $client['Zdjecie'];
        }

        header("Location: ?page=dashboard");
        exit();
    } else {
        $error = "Nieprawidłowy email lub hasło!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
</head>
<body>
<div class="login-first-container">
    <div class="login-first-content">
        <form class="login-first-form" method="POST" action="">
            <h3>Welcome Back</h3>
            <p>Let's Login</p>
            <input type="email" name="email" class="login-first-input" placeholder="Email" required />
            <input type="password" name="password" class="login-first-input" placeholder="Password" required />
            <button type="submit" class="login-first-button">Login</button>
            <a href="?page=register" class="login-first-register">Don&apos;t have an account? <b>Register</b></a>
        </form>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>