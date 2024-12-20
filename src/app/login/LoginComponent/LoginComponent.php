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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (empty($password)) {
        $error = "Password cannot be empty";
    }
    else if ($user && password_verify($password, $user['Haslo'])) {
        $_SESSION['LoginId'] = $user['Id_Logowania'];
        $_SESSION['email'] = $user['Email'];
        $Id_Logowania = $user['Id_Logowania'];

        $stmt = $conn->prepare("SELECT Id_Klienta, Imie, Nazwisko, Id_Kontaktu, Id_Adresu, Zdjecie,isAdmin FROM Klienci WHERE Id_Logowania = :id_logowania");
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
            $_SESSION['isAdmin'] = $client['isAdmin'];
        }

        header("Location: ?page=home");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<div class="login-first-container">
    <?php if (isset($error)): ?>
        <div class="error-message-login"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message-login"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <img src="/public/background5.jpg" alt="background-image" class="hero-background-image">
    <div class="login-first-content">
        <form class="login-first-form" method="POST" action="">
            <h3>Welcome Back</h3>
            <p>Let's Login</p>
            <input type="email" name="email" class="login-first-input" placeholder="Email" required />
            <input type="password" name="password" class="login-first-input" placeholder="Password" required />
            <button type="submit" class="login-first-button">Login</button>
            <a href="?page=register" class="login-first-register">Don&apos;t have an account? <b>Register</b></a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('.login-first-form');
        form.addEventListener('submit', (event) => {
            const email = form.email.value.trim();
            const password = form.password.value;

            const errorMessages = document.querySelectorAll('.error-message-login');
            errorMessages.forEach(msg => msg.remove());

            let valid = true;

            if (!validateEmail(email)) {
                showError(form.email, "Invalid email address");
                valid = false;
            }

            else if (password.length < 1) {
                showError(form.password, "Password cannot be empty");
                valid = false;
            }

            else if (!valid) {
                event.preventDefault();
            }
        });

        function showError(input, message) {
            const error = document.createElement('div');
            error.className = 'error-message-login2';
            error.textContent = message;
            input.insertAdjacentElement('afterend', error);
        }

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>

