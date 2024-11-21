
<?php
require 'db.php';
function generateUniqueId() {
    return rand(1, 2147483647);
}

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

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($repeat_password)) {
        $error = "Please fill in all fields!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address!";
    } elseif ($password !== $repeat_password) {
        $error = "Passwords do not match!";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM Logowanie WHERE Email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $error = "This email is already registered!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $idKontaktu = generateUniqueId();
                $idAdresu = generateUniqueId();
                $idLogowania = generateUniqueId();
                $idKlienta = generateUniqueId();

                $stmt = $conn->prepare("INSERT INTO Kontakty (Email, NumerTelefonu, Id_Kontaktu) VALUES (:email, :phone_number, :id_kontaktu)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone_number', $phoneNumber);
                $stmt->bindParam(':id_kontaktu', $idKontaktu);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO Adresy (Ulica, Numer_Domu, Kod_Pocztowy, Miasto, Id_Adresu) VALUES (:street, :house_number, :postal_code, :city, :id_adresu)");
                $stmt->bindParam(':street', $street);
                $stmt->bindParam(':house_number', $houseNumber);
                $stmt->bindParam(':postal_code', $postalCode);
                $stmt->bindParam(':city', $city);
                $stmt->bindParam(':id_adresu', $idAdresu);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO Logowanie (Email, Haslo, Id_Logowania) VALUES (:email, :password, :id_logowania)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':id_logowania', $idLogowania);
                $stmt->execute();

                $stmt = $conn->prepare("INSERT INTO Klienci (Id_Klienta,Imie, Nazwisko, Id_Kontaktu, Id_Adresu, Zdjecie, Id_Logowania) VALUES (:id_klienta,:firstname, :lastname, :id_kontaktu, :id_adresu, :photo, :id_logowania)");
                $stmt->bindParam(':id_klienta', $idKlienta);
                $stmt->bindParam(':firstname', $firstname);
                $stmt->bindParam(':lastname', $lastname);
                $stmt->bindParam(':id_kontaktu', $idKontaktu);
                $stmt->bindParam(':id_adresu', $idAdresu);
                $stmt->bindParam(':photo', $photo);
                $stmt->bindParam(':id_logowania', $idLogowania);
                $stmt->execute();

                $_SESSION['success'] = "Registration successful! Please log in.";
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
    <div class="register-first-content">
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <form class="register-first-form" action="?page=register" method="POST">
            <h3>Create Your Account</h3>
            <p>Let's Register</p>
            <input type="text" class="register-first-input" name="firstname" placeholder="Firstname" required />
            <input type="text" class="register-first-input" name="lastname" placeholder="Lastname" required />
            <input type="email" class="register-first-input" name="email" placeholder="Email" required />
            <input type="password" class="register-first-input" name="password" placeholder="Password" required />
            <input type="password" class="register-first-input" name="repeat_password" placeholder="Repeat Password" required />
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
