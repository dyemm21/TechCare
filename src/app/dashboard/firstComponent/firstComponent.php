<?php

require 'logout.php';
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$LoginId= $_SESSION['LoginId'] ?? null;
$email = $_SESSION['email'] ?? null;
$firstname = $_SESSION['firstname'] ?? null;
$lastname = $_SESSION['lastname'] ?? null;
$photo = $_SESSION['photo'] ?? null;
$ContactId = $_SESSION['ContactId'] ?? null;
$AddressId = $_SESSION['AddressId'] ?? null;
$ClientId = $_SESSION['ClientId'] ?? null;

if (!isset($conn)) {
    die("Błąd: brak zmiennej \$pdo. Upewnij się, że db.php jest poprawnie zaimportowany.");
}

if ($AddressId) {

    $stmt = $conn->prepare("SELECT Ulica, Numer_Domu, Kod_Pocztowy, Miasto FROM adresy WHERE Id_Adresu = ?");
    $stmt->execute([$AddressId]);
    $address= $stmt->fetch(PDO::FETCH_ASSOC);
    if($address)
    {
        $street = $address['Ulica'];
        $house_number = $address['Numer_Domu'];
        $postcode = $address['Kod_Pocztowy'];
        $city= $address['Miasto'];
    }
    else {
        echo "Użytkownik nie znaleziony.";
    }

} else {
    echo "Brak zalogowanego użytkownika.";
}
if ($ContactId) {

    $stmt = $conn->prepare("SELECT NumerTelefonu FROM kontakty WHERE Id_Kontaktu = ?");
    $stmt->execute([$ContactId]);
    $contact= $stmt->fetch(PDO::FETCH_ASSOC);
    if($contact)
    {
        $phone_number = $contact['NumerTelefonu'];
    }
    else {
        echo "Użytkownik nie znaleziony.";
    }

} else {
    echo "Brak zalogowanego użytkownika.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newFirstname = $_POST['firstname'] ?? null;
    $newLastname = $_POST['lastname'] ?? null;

    if ($LoginId && $newFirstname && $newLastname) {
        $stmt = $conn->prepare("UPDATE klienci SET Imie = ?, Nazwisko = ? WHERE Id_Logowania  = ?");
        $stmt->execute([$newFirstname, $newLastname, $LoginId]);

        $_SESSION['firstname'] = $newFirstname;
        $_SESSION['lastname'] = $newLastname;


    } else {
        echo "Nie udalo sie zmienic profilu uzytkownika";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_contact'])) {
    $newPhoneNumber = $_POST['phone_number'] ?? null;
    $newEmail= $_POST['email'] ?? null;

    if ($LoginId && $newPhoneNumber && $newEmail) {
        $stmt = $conn->prepare("UPDATE kontakty SET Email = ?, NumerTelefonu = ? WHERE Id_Kontaktu  = ?");
        $stmt->execute([$newEmail, $newPhoneNumber, $ContactId]);

        $stmt = $conn->prepare("UPDATE logowanie SET Email = ? WHERE Id_Logowania  = ?");
        $stmt->execute([$newEmail, $LoginId]);

        $_SESSION['email'] = $newEmail;

    } else {
        echo "Nie udalo sie zmienic profilu kontaktu uzytkownika";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_address'])) {
    $newCity = $_POST['city'] ?? null;
    $newStreet = $_POST['street'] ?? null;
    $newPostCode = $_POST['postcode'] ?? null;
    $newHouseNumber = $_POST['house_number'] ?? null;

    if ($LoginId && $newCity && $newStreet && $newPostCode && $newHouseNumber) {
        $stmt = $conn->prepare("UPDATE adresy SET Ulica = ?, Numer_Domu = ?, Kod_Pocztowy = ?, Miasto = ? WHERE Id_Adresu  = ?");
        $stmt->execute([$newStreet, $newHouseNumber, $newPostCode, $newCity, $AddressId]);

    } else {
        echo "Nie udalo sie zmienic profilu adresu uzytkownika";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $newCurrentPassword = $_POST['current_password'] ?? null;
    $newPassword = $_POST['new_password'] ?? null;
    $repeatNewPassword = $_POST['repeat_new_password'] ?? null;

    $stmt = $conn->prepare("SELECT Haslo FROM logowanie WHERE Id_Logowania = ?");
    $stmt->execute([$LoginId]);
    $user= $stmt->fetch(PDO::FETCH_ASSOC);
    if($user)
    {
        $password = $user['Haslo'];
    }
    else {
        echo "Użytkownik nie znaleziony.";
    }


    if ($user && $newCurrentPassword && $newPassword && $repeatNewPassword && $newPassword === $repeatNewPassword && password_verify($newCurrentPassword,$password)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE logowanie SET Haslo = ? WHERE Id_Logowania  = ?");
        $stmt->execute([$hashedPassword, $LoginId]);

    } else {
        echo "Nie udalo sie zmienic hasła uzytkownika";
    }
}

$section = isset($_POST['section']) ? $_POST['section'] : 'profile';
$edit = isset($_POST['edit']) ? $_POST['edit'] : 'profile';

?>


<div class="page-dashboard-first-back">
    <div class="page-dashboard-first-container">
        <div class="page-dashboard">
            <div class="page-dashboard-menu">
                <div class="page-dashboard-menu-head">
                    <img src="/public/dashboard_icon.svg" alt="dashboard-icon"/>
                    <h1>Dashboard</h1>
                </div>
                <span></span>
                <div class="page-dashboard-menu-nav">
                    <form method="post" class="page-dashboard-menu-sections">
                        <button type="submit" name="section" value="profile">
                            <img src="/public/profile_icon.svg" alt="profile-icon" class="page-dashboard-menu-icon"/>
                            <h3>Profil</h3>
                        </button>
                        <button type="submit" name="section" value="password">
                            <img src="/public/password_icon.svg" alt="contact-icon" class="page-dashboard-menu-icon"/>
                            <h3>Hasło</h3>
                        </button>
                        <button type="submit" name="section" value="address">
                            <img src="/public/address_icon.svg" alt="profile-icon" class="page-dashboard-menu-icon"/>
                            <h3>Adres</h3>
                        </button>
                        <button type="submit" name="section" value="contact">
                            <img src="/public/contact_icon.svg" alt="contact-icon" class="page-dashboard-menu-icon"/>
                            <h3>Kontakt</h3>
                        </button>
                        <button type="submit" name="section" value="orders">
                            <img src="/public/order_icon.svg" alt="order-icon" class="page-dashboard-menu-icon"/>
                            <h3>Zamówienia</h3>
                        </button>
                        <div class="page-dashboard-button-logout">
                            <button type="submit" name="logout">Wyloguj</button>
                        </div>

                    </form>

                    <div class="page-dashboard-menu-user-profile">
                        <img src="/public/noavatar.png" alt="user-profile">
                        <h3><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></h3>
                        <p><?php echo htmlspecialchars($email); ?></p>
                    </div>
                </div>
            </div>
            <div class="page-dashboard-content">
                <div class="page-about-content-top">
                    <?php if ($section === 'profile'): ?>
<!--                        <div class="page-about-content-top-first">-->
<!--                            <div class="page-about-content-top-title">-->
<!--                                <h5>Your photo</h5>-->
<!--                                <p>This will be displayed on your profile.</p>-->
<!--                            </div>-->
<!--                            <div class="page-about-content-imageContainer">-->
<!--                                <img src="/public/noavatar.png"/>-->
<!--                                <div class="page-about-content-image-update">Update</div>-->
<!--                                <div class="page-about-content-image-delete">Delete</div>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="page-about-content-top-second">
                            <div class="page-about-content-top-second-data">
                                <h5>Imie</h5>
                                <input type="text" placeholder="Firstname" value=<?php echo ($firstname); ?>>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Nazwisko</h5>
                                <input type="text" placeholder="Lastname" value=<?php echo ($lastname); ?>>
                            </div>
                        </div>
                        <button class="page-dashboard-edit-profile" name="edit" value="profile">
                            <img src="/public/edit-icon2.svg" alt="edit-icon"/>
                        </button>

                    <?php endif; ?>
                    <?php if ($section === 'password'): ?>
                        <div class="page-about-content-top-second">
                            <div class="page-about-content-top-second-data">
                                <h5>Obecene Hasło</h5>
                                <input type="password" placeholder="Password" value="dsab123"/>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Nowe Hasło</h5>
                                <input type="password" placeholder="Password" value="sdajkk123d45"/>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Powtórz Nowe Hasło</h5>
                                <input type="password" placeholder="Password" value="sdajkk123d45"/>
                            </div>
                        </div>
                        <button class="page-dashboard-edit-profile" name="edit" value="password">
                            <img src="/public/edit-icon2.svg" alt="edit-icon"/>
                        </button>
                    <?php endif; ?>
                    <?php if ($section === 'address'): ?>
                        <div class="page-about-content-top-second-contact">
                            <div class="page-about-content-top-first-contact-subsections">
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Miasto</h5>
                                    <input type="text" placeholder="City" value=<?php echo ($city); ?>>
                                </div>
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Kod Pocztowy</h5>
                                    <input type="text" placeholder="Postal Code" value=<?php echo ($postcode); ?>>
                                </div>
                            </div>
                            <div class="page-about-content-top-first-contact-subsections">
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Ulica</h5>
                                    <input type="text" placeholder="Street" value=<?php echo ($street); ?>>
                                </div>
                                <div class="page-about-content-top-second-contact-data">
                                    <h5>Numer Domu</h5>
                                    <input type="number" placeholder="Number" value=<?php echo ($house_number); ?>>
                                </div>
                            </div>
                        </div>
                        <button class="page-dashboard-edit-profile" name="edit" value="address">
                            <img src="/public/edit-icon2.svg" alt="edit-icon"/>
                        </button>
                    <?php endif; ?>
                    <?php if ($section === 'contact'): ?>
                        <div class="page-about-content-top-second">
                            <div class="page-about-content-top-second-data">
                                <h5>Numer Telefonu</h5>
                                <input type="number" placeholder="Phone Number" value=<?php echo ($phone_number); ?>>
                            </div>
                            <div class="page-about-content-top-second-data">
                                <h5>Email</h5>
                                <input type="email" placeholder="Email" value=<?php echo ($email); ?>>
                            </div>
                        </div>
                        <button class="page-dashboard-edit-profile" name="edit" value="contact">
                            <img src="/public/edit-icon2.svg" alt="edit-icon"/>
                        </button>
                    <?php endif; ?>
                    <?php if ($section === 'orders'): ?>
                    <?php endif; ?>
                </div>
                <div class="page-about-content-bottom">
                    Orders
                </div>
            </div>
        </div>
        <div id="modal" class="modal">
            <div class="modal-content">
                <button id="close-modal" class="close-modal">&times;</button>
                <?php if ($section === 'profile'): ?>
                <h2>Edycja Profilu</h2>
                <form method="post">
                    <div class="page-dashboard-edit-profile-container">
                        <div class="page-dashboard-edit-profile-container-data">
                            <h5>Imie</h5>
                            <input type="text" name="firstname" placeholder="Firstname" value="<?php echo ($firstname); ?>" required>
                        </div>
                        <div class="page-dashboard-edit-profile-container-data">
                            <h5>Nazwisko</h5>
                            <input type="text" name="lastname" placeholder="Lastname" value="<?php echo ($lastname); ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="update_profile" class="page-dashboard-edit-profile-container-button">Zapisz zmiany</button>
                </form>
                <?php endif; ?>
                <?php if ($section === 'password'): ?>
                    <h2>Edycja Hasła</h2>
                    <form method="post">
                        <div class="page-dashboard-edit-profile-container">
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Obecne Hasło</h5>
                                <input type="password" name="current_password" placeholder="Password" required>
                            </div>
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Nowe Hasło</h5>
                                <input type="password" name="new_password" placeholder="Password" required>
                            </div>
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Powtórz Nowe Hasło</h5>
                                <input type="password" name="repeat_new_password" placeholder="Password" required>
                            </div>
                        </div>
                        <button type="submit" name="update_password" class="page-dashboard-edit-profile-container-button">Zapisz zmiany</button>
                    </form>
                <?php endif; ?>
                <?php if ($section === 'address'): ?>
                    <h2>Edycja Adresu</h2>
                    <form method="post">
                        <div class="page-dashboard-edit-profile-container">
                            <div class="page-about-content-top-first-contact-subsections">
                                <div class="page-dashboard-edit-profile-container-address">
                                    <h5>Miasto</h5>
                                    <input type="text" name= "city" placeholder="City" value=<?php echo ($city); ?>>
                                </div>
                                <div class="page-dashboard-edit-profile-container-address">
                                    <h5>Kod Pocztowy</h5>
                                    <input type="text" name= "postcode" placeholder="Postal Code" value=<?php echo ($postcode); ?>>
                                </div>
                            </div>
                            <div class="page-about-content-top-first-contact-subsections">
                                <div class="page-dashboard-edit-profile-container-address">
                                    <h5>Ulica</h5>
                                    <input type="text" name= "street" placeholder="Street" value=<?php echo ($street); ?>>
                                </div>
                                <div class="page-dashboard-edit-profile-container-address">
                                    <h5>Numer Domu</h5>
                                    <input type="number" name= "house_number" placeholder="Number" value=<?php echo ($house_number); ?>>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="update_address" class="page-dashboard-edit-profile-container-button">Zapisz zmiany</button>
                    </form>
                <?php endif; ?>
                <?php if ($section === 'contact'): ?>
                    <h2>Edycja Kontaktu</h2>
                    <form method="post">
                        <div class="page-dashboard-edit-profile-container">
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Numer Telefonu</h5>
                                <input type="number" name="phone_number" placeholder="Firstname" value="<?php echo ($phone_number); ?>" required>
                            </div>
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Email</h5>
                                <input type="email" name="email" placeholder="Lastname" value="<?php echo ($email); ?>" required>
                            </div>
                        </div>
                        <button type="submit" name="update_contact" class="page-dashboard-edit-profile-container-button">Zapisz zmiany</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<script>
    const modal = document.getElementById('modal');
    const editButton = document.querySelector('.page-dashboard-edit-profile');
    const closeModal = document.getElementById('close-modal');

    editButton.addEventListener('click', (e) => {
        e.preventDefault();
        modal.style.display = 'flex';
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>

