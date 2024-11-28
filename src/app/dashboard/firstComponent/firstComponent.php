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

$sql = "SELECT Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu FROM urządzenia WHERE Id_Klienta = :id_klienta";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_klienta', $ClientId, PDO::PARAM_INT);
$stmt->execute();
$AllDevicesFromClient = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "
    SELECT 
        z.Id_Zlecenia,
        z.Id_Urządzenia,
        z.Id_Pracownika,
        z.Data_Przyjęcia,
        z.Opis_Problemu AS Opis_Zlecenia,
        z.Id_Statusu,
        s.Nazwa AS Nazwa_Statusu,
        z.Data_Zakończenia,
        z.Id_Usługi,
        u.Id_Klienta,
        u.Id_TypuUrządzenia,
        u.Marka,
        u.Model,
        u.Numer_Seryjny,
        u.Opis_Problemu AS Opis_Urządzenia,
        p.Imie AS Imie_Pracownika,
        p.Nazwisko AS Nazwisko_Pracownika,
        p.Stanowisko AS Stanowisko_Pracownika
    FROM zlecenia z
    INNER JOIN urządzenia u ON z.Id_Urządzenia = u.Id_Urządzenia
    INNER JOIN status s ON z.Id_Statusu = s.Id_Statusu
    INNER JOIN pracownicy p ON z.Id_Pracownika = p.Id_Pracownika
    WHERE u.Id_Klienta = :id_klienta
    ORDER BY z.Data_Przyjęcia DESC;
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_klienta', $ClientId, PDO::PARAM_INT);
$stmt->execute();
$AllOrdersFromClient = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
<!--                        <h1 class="page-dashboard-orders-user-title">Lista urządzeń klienta</h1>-->
<!--                        <div class="page-dashboard-orders-user-devices-list">-->
<!--                            --><?php //if (!empty($AllDevicesFromClient)): ?>
<!--                                --><?php //foreach ($AllDevicesFromClient as $device): ?>
<!--                                    <div class="page-dashboard-orders-user-single-device">-->
<!--                                        <h3>--><?php //echo htmlspecialchars($device['Marka'] . " " . $device['Model']); ?><!--</h3>-->
<!--                                    </div>-->
<!--                                --><?php //endforeach; ?>
<!--                            --><?php //else: ?>
<!--                                <p>Brak dodanych urządzeń przez klienta.</p>-->
<!--                            --><?php //endif; ?>
<!--                        </div>-->
                    <?php endif; ?>
                </div>
                <div class="page-about-content-bottom">
                    <?php if (!empty($AllOrdersFromClient)): ?>
                        <?php foreach ($AllOrdersFromClient as $order): ?>
                            <div class="page-dashboard-orders-user-single-order">
                                <div class="page-dashboard-orders-user-single-section-title">Id: </div>
                                <div class="page-dashboard-orders-user-single-id"><?php echo htmlspecialchars($order['Id_Zlecenia']); ?></div>
                                <div class="page-dashboard-orders-user-single-section-title">Przyjęcie: </div>
                                <div class="page-dashboard-orders-user-single-date-reception"><?php echo htmlspecialchars($order['Data_Przyjęcia']); ?></div>
                                <div class="page-dashboard-orders-user-single-section-title">Zakończenie: </div>
                                <div class="page-dashboard-orders-user-single-data-ending"><?php echo $order['Data_Zakończenia'] === null ? "W trakcie" : htmlspecialchars($order['Data_Zakończenia']); ?></div>
                                <div class="page-dashboard-orders-user-single-section-device"><?php echo htmlspecialchars($order['Marka'] . " " . $order['Model']); ?></div>
                                <div class="page-dashboard-orders-user-single-show" type="submit" data-order="order" >Pokaż</div>
                                <div class="page-dashboard-orders-user-single-delete">Usuń</div>
                                <input type="hidden" class="page-dashboard-orders-user-single-serial_number" value="<?php echo htmlspecialchars($order['Numer_Seryjny']); ?>"/>
                                <input type="hidden" class="page-dashboard-orders-user-single-issue_desc" value="<?php echo htmlspecialchars($order['Opis_Zlecenia']); ?>"/>
                                <input type="hidden" class="page-dashboard-orders-user-single-status" value="<?php echo htmlspecialchars($order['Nazwa_Statusu']); ?>"/>
                                <input type="hidden" class="page-dashboard-orders-user-single-employee-name" value="<?php echo htmlspecialchars($order['Imie_Pracownika']); ?>"/>
                                <input type="hidden" class="page-dashboard-orders-user-single-employee-surname" value="<?php echo htmlspecialchars($order['Nazwisko_Pracownika']); ?>"/>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Brak dodanych urządzeń przez klienta.</p>
                    <?php endif; ?>
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
                                <input type="password" name="current_password" placeholder="Current Password" required>
                            </div>
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Nowe Hasło</h5>
                                <input type="password" name="new_password" placeholder="New Password" required>
                            </div>
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Powtórz Nowe Hasło</h5>
                                <input type="password" name="repeat_new_password" placeholder="New Password" required>
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
                                <input type="number" name="phone_number" placeholder="Phone Number" value="<?php echo ($phone_number); ?>" required>
                            </div>
                            <div class="page-dashboard-edit-profile-container-data">
                                <h5>Email</h5>
                                <input type="email" name="email" placeholder="Email" value="<?php echo ($email); ?>" required>
                            </div>
                        </div>
                        <button type="submit" name="update_contact" class="page-dashboard-edit-profile-container-button">Zapisz zmiany</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div id="modal-order" class="modal-order">
            <div class="modal-content-order">
                <button id="close-modal-order" class="close-modal-order">&times;</button>

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
<script>
    const modalOrder = document.getElementById('modal-order');
    const buttonsOrder = document.querySelectorAll('.page-dashboard-orders-user-single-show');
    const closeModalOrderButton = document.getElementById('close-modal-order');

    function closeModalOrder() {
        modalOrder.style.display = 'none';
    }

    buttonsOrder.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const orderRow = button.closest('.page-dashboard-orders-user-single-order');
            const orderId = orderRow.querySelector('.page-dashboard-orders-user-single-id').textContent;
            const orderDevice = orderRow.querySelector('.page-dashboard-orders-user-single-section-device').textContent;
            const orderDateAcceptance = orderRow.querySelector('.page-dashboard-orders-user-single-date-reception').textContent;
            const orderDateCompletion = orderRow.querySelector('.page-dashboard-orders-user-single-data-ending').textContent;
            const orderSerialNumber = orderRow.querySelector('.page-dashboard-orders-user-single-serial_number').value;
            const orderIssueDesc = orderRow.querySelector('.page-dashboard-orders-user-single-issue_desc').value;
            const orderStatus = orderRow.querySelector('.page-dashboard-orders-user-single-status').value;
            const orderEmployeeName= orderRow.querySelector('.page-dashboard-orders-user-single-employee-name').value;
            const orderEmployeeSurname= orderRow.querySelector('.page-dashboard-orders-user-single-employee-surname').value;

            const modalContent = modalOrder.querySelector('.modal-content-order');
            modalContent.innerHTML = `
                <button id="close-modal-order" class="close-modal-order">&times;</button>
                <h2 class="modal-order-details-title">Szczegóły Zamówienia</h2>
                <div class="modal-order-details">
                    <div class="modal-order-detail">
                        <p>ID Zamówienia:</p>
                        <div>${orderId}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Urządzenie:</p>
                        <div>${orderDevice}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Numer Seryjny:</p>
                        <div>${orderSerialNumber}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Data Przyjęcia:</p>
                        <div>${orderDateAcceptance}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Data Zakończenia:</p>
                        <div>${orderDateCompletion}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Status Zamówienia:</p>
                        <div>${orderStatus}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Serwisant</p>
                        <div>${orderEmployeeName} ${orderEmployeeSurname}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Opis Zlecenia Naprawy:</p>
                        <div>${orderIssueDesc}</div>
                    </div>
                </div>
            `;

            const closeButton = modalContent.querySelector('.close-modal-order');
            closeButton.addEventListener('click', closeModalOrder);

            modalOrder.style.display = 'flex';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modalOrder) {
            closeModalOrder();
        }
    });
</script>

