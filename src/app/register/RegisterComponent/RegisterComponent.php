
<?php
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($conn)) {
    die("Błąd: brak zmiennej \$conn. Upewnij się, że db.php jest poprawnie zaimportowany.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];
    $phoneNumber = trim($_POST['phone_number']);
    $street = trim($_POST['street']);
    $houseNumber = trim($_POST['house_number']);
    $postalCode = trim($_POST['postal_code']);
    $city = trim($_POST['city']);
    $photo = trim($_POST['photo']);

    if (strlen($firstname) < 2) {
        $error = "Firstname must have at least 2 characters";
    } elseif (strlen($lastname) < 2) {
        $error = "Lastname must have at least 2 characters";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]+$/", $email)) {
        $error = "Invalid email address";
    }

    else if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($repeat_password)) {
        $error = "Please fill in all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address";
    } elseif ($password !== $repeat_password) {
        $error = "Passwords do not match";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM Logowanie WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $error = "This email is already registered!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


                $stmt = $conn->prepare("INSERT INTO Kontakty (Email, NumerTelefonu) VALUES (:email, :phone_number)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone_number', $phoneNumber);
                $stmt->execute();

                $idKontaktu = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO Adresy (Ulica, Numer_Domu, Kod_Pocztowy, Miasto) VALUES (:street, :house_number, :postal_code, :city)");
                $stmt->bindParam(':street', $street);
                $stmt->bindParam(':house_number', $houseNumber);
                $stmt->bindParam(':postal_code', $postalCode);
                $stmt->bindParam(':city', $city);
                $stmt->execute();

                $idAdresu = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO Logowanie (Email, Haslo) VALUES (:email, :password)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->execute();

                $idLogowania = $conn->lastInsertId();

                $stmt = $conn->prepare("INSERT INTO Klienci (Imie, Nazwisko, Id_Kontaktu, Id_Adresu, Zdjecie, Id_Logowania) VALUES (:firstname, :lastname, :id_kontaktu, :id_adresu, :photo, :id_logowania)");
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':id_kontaktu', $idKontaktu);
                $stmt->bindParam(':id_adresu', $idAdresu);
                $stmt->bindParam(':photo', $photo);
                $stmt->bindParam(':id_logowania', $idLogowania);
                $stmt->execute();

                $_SESSION['success'] = "Registration successful";
                header('Location: ?page=login');
                exit();
            }
        } catch (PDOException $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<div class="register-first-container">
    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <img src="/public/background5.jpg" alt="background-image" class="hero-background-image">
    <div class="register-first-content">
        <form class="register-first-form" action="?page=register" method="POST">
            <h3>Create Your Account</h3>
            <p>Let's Register</p>
            <input type="text" class="register-first-input" name="firstname" placeholder="Firstname" required/>
            <input type="text" class="register-first-input" name="lastname" placeholder="Lastname"  required/>
            <input type="email" class="register-first-input" name="email" placeholder="Email" required />
            <input type="password" class="register-first-input" name="password" placeholder="Password" required/>
            <input type="password" class="register-first-input" name="repeat_password" placeholder="Repeat Password" required/>
            <input type="hidden" class="register-first-input" name="phone_number" value=" "/>
            <input type="hidden" class="register-first-input" name="street"  value=" " />
            <input type="hidden" class="register-first-input" name="house_number"  value=" "/>
            <input type="hidden" class="register-first-input" name="postal_code" value=" " />
            <input type="hidden" class="register-first-input" name="city"  value=" "/>
            <input type="hidden" class="register-first-input" name="photo"  value=" "/>
            <button type="submit" class="register-first-button">Register</button>
            <a href="?page=login" class="register-first-register">Have an account? <b>Login</b></a>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('.register-first-form');
        form.addEventListener('submit', (event) => {
            const email = form.email.value.trim();

            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => msg.remove());

            else if (!validateEmail(email)) {
                showError(form.email, "Invalid email address");
                event.preventDefault();
            }
        });

        function showError(input, message) {
            const error = document.createElement('div');
            error.className = 'error-message2';
            error.textContent = message;
            input.insertAdjacentElement('afterend', error);
        }

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    });
</script>




