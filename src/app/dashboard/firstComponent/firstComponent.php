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
$isAdmin = $_SESSION['isAdmin'] ?? null;

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
        z.Opis_Problemu,
        z.Id_Statusu,
        s.Nazwa AS Nazwa_Statusu,
        z.Data_Zakończenia,
        z.Id_Usługi,
        u.Id_Klienta,
        u.Id_TypuUrządzenia,
        u.Marka,
        u.Model,
        u.Numer_Seryjny,
        p.Imie AS Imie_Pracownika,
        p.Nazwisko AS Nazwisko_Pracownika,
        p.Stanowisko AS Stanowisko_Pracownika,
        z.Id_Płatności,
        x.Nazwa_Płatności,
        tu.Nazwa AS Nazwa_TypuUrządzenia,
        serv.Nazwa AS Nazwa_Usługi,
        serv.Opis AS Opis_Usługi,
        serv.Cena AS Cena_Usługi
        
    FROM zlecenia z
    INNER JOIN urządzenia u ON z.Id_Urządzenia = u.Id_Urządzenia
    INNER JOIN typurządzenia tu ON u.Id_TypuUrządzenia = tu.Id_TypuUrządzenia
    INNER JOIN usługi serv ON z.Id_Usługi = serv.Id_Usługi
    INNER JOIN status s ON z.Id_Statusu = s.Id_Statusu
    INNER JOIN pracownicy p ON z.Id_Pracownika = p.Id_Pracownika
    INNER JOIN płatność x ON z.Id_Płatności = x.Id_Płatności
    WHERE u.Id_Klienta = :id_klienta
    ORDER BY z.Data_Przyjęcia DESC;
";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_klienta', $ClientId, PDO::PARAM_INT);
$stmt->execute();
$AllOrdersFromClient = $stmt->fetchAll(PDO::FETCH_ASSOC);


if($isAdmin)
{
    $sql = "
        SELECT 
           k.Id_Klienta,
           k.Imie,
           k.Nazwisko,
           k.Id_Kontaktu,
           k.Id_Adresu,
           kon.Email,
           kon.NumerTelefonu,
           ad.Ulica,
           ad.Numer_domu,
           ad.Kod_Pocztowy,
           ad.Miasto
           
        FROM klienci k
        INNER JOIN kontakty kon ON k.Id_Kontaktu = kon.Id_Kontaktu
        INNER JOIN adresy ad ON k.Id_Adresu = ad.Id_Adresu
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $AllUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if($isAdmin)
{
    $sql = "
        SELECT 
           p.Id_Pracownika,
           p.Imie,
           p.Nazwisko,
           p.Stanowisko,
           p.Id_Kontaktu,
           p.Id_Adresu,
           k.Email,
           k.NumerTelefonu,
           k.NumerTelefonu,
           a.Ulica,
           a.Numer_domu,
           a.Kod_Pocztowy,
           a.Miasto
           
        FROM pracownicy p
        INNER JOIN kontakty k ON p.Id_Kontaktu = k.Id_Kontaktu
        INNER JOIN adresy a ON p.Id_Adresu = a.Id_Adresu

    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $AllEmployees = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $OrderId = $_POST['delete_order_id'] ?? null;
    $OrderNameIdStatus = $_POST['delete_order_id_status'] ?? null;
    $OrderNameStatus = $_POST['delete_order_name_status'] ?? null;

    if($OrderNameStatus !== 'Nowe')
    {
        return;
    }
    else if ($OrderId && $OrderNameIdStatus )
    {
        $newStatusId = "2116961734";
        $stmt = $conn->prepare("UPDATE zlecenia SET Id_Statusu = ? WHERE Id_Zlecenia = ?");
        $stmt->execute([$newStatusId, $OrderId]);
    } else
    {
        echo "Nie udało się zmienić statusu zamówienia na anulowane";
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
                        <?php if ($isAdmin): ?>
                            <button type="submit" name="section" value="admin">
                                <img src="/public/admin-icon.svg" alt="admin-icon" class="page-dashboard-menu-icon" />
                                <h3>Admin</h3>
                            </button>
                        <?php endif; ?>
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
                    <?php if ($section === 'admin'): ?>
                        <div class="page-dashboard-admin">
                            <div class="page-dashboard-admin-users">
                                <h3>Użytkownicy</h3>
                                <?php if (!empty($AllUsers)): ?>
                                    <?php foreach ($AllUsers as $users): ?>
                                        <div class="page-dashboard-admin-single-user" type="submit" data-user="user">
                                            <div class="page-dashboard-orders-user-single-section-title">Id: </div>
                                            <div class="page-dashboard-admin-single-user-id" type="submit" data-user="user"><?php echo htmlspecialchars($users['Id_Klienta']); ?></div>
                                            <div class="page-dashboard-orders-user-single-section-title">Imie i Nazwisko: </div>
                                            <div class="page-dashboard-admin-single-user-name" ><?php echo htmlspecialchars($users['Imie'] . " " . $users['Nazwisko']); ?></div>
                                            <input type="hidden" class="page-dashboard-admin-user-id" value="<?php echo htmlspecialchars($users['Id_Klienta']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-firstname" value="<?php echo htmlspecialchars($users['Imie']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-lastname" value="<?php echo htmlspecialchars($users['Nazwisko']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-email" value="<?php echo htmlspecialchars($users['Email']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-phone" value="<?php echo htmlspecialchars($users['NumerTelefonu']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-street" value="<?php echo htmlspecialchars($users['Ulica']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-house-number" value="<?php echo htmlspecialchars($users['Numer_domu']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-post-code" value="<?php echo htmlspecialchars($users['Kod_Pocztowy']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-user-city" value="<?php echo htmlspecialchars($users['Miasto']); ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Brak dodanych urządzeń przez klienta.</p>
                                <?php endif; ?>
                            </div>
                            <div class="page-dashboard-admin-center-lines">
                                <span></span>
                                <span></span>
                            </div>
                            <div class="page-dashboard-admin-employees">
                                <h3>Pracownicy</h3>
                                <?php if (!empty($AllEmployees)): ?>
                                    <?php foreach ($AllEmployees as $employee): ?>
                                        <div class="page-dashboard-admin-single-employee" type="submit" data-user="user">
                                            <div class="page-dashboard-orders-user-single-section-title">Id: </div>
                                            <div class="page-dashboard-admin-single-user-id"><?php echo htmlspecialchars($employee['Id_Pracownika']); ?></div>
                                            <div class="page-dashboard-orders-user-single-section-title">Imie i Nazwisko: </div>
                                            <div class="page-dashboard-admin-single-user-name"><?php echo htmlspecialchars($employee['Imie'] . " " . $employee['Nazwisko']); ?></div>
                                            <input type="hidden" class="page-dashboard-admin-employee-id" value="<?php echo htmlspecialchars($employee['Id_Pracownika']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-firstname" value="<?php echo htmlspecialchars($employee['Imie']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-lastname" value="<?php echo htmlspecialchars($employee['Nazwisko']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-position" value="<?php echo htmlspecialchars($employee['Stanowisko']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-email" value="<?php echo htmlspecialchars($employee['Email']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-phone" value="<?php echo htmlspecialchars($employee['NumerTelefonu']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-street" value="<?php echo htmlspecialchars($employee['Ulica']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-house-number" value="<?php echo htmlspecialchars($employee['Numer_domu']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-post-code" value="<?php echo htmlspecialchars($employee['Kod_Pocztowy']); ?>" />
                                            <input type="hidden" class="page-dashboard-admin-employee-city" value="<?php echo htmlspecialchars($employee['Miasto']); ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Brak dodanych urządzeń przez klienta.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
                <div class="page-dashboard-content-bottom">
                    <div class="page-dashboard-content-bottom-details">
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
                                    <form method="POST">
                                        <button class="page-dashboard-orders-user-single-delete"
                                                type="submit" name="delete_order"
                                                style="background-color: <?php
                                                // Change background color based on the order's status
                                                if ($order['Nazwa_Statusu'] === 'Nowe') {
                                                    echo "#b10404";  // Red for 'Nowe'
                                                } elseif ($order['Nazwa_Statusu'] === 'Ukonczone') {
                                                    echo "#222222";  // Dark for 'Ukończone'
                                                } elseif ($order['Nazwa_Statusu'] === 'Anulowane') {
                                                    echo "#c8c8c8";  // Light gray for 'Anulowane'
                                                } else {
                                                    echo "#049a52";  // Green for other statuses (Realizacja)
                                                }
                                                ?>">

                                            <?php
                                            // Display the status text inside the button
                                            if ($order['Nazwa_Statusu'] === 'Nowe') {
                                                echo "Anuluj";
                                            } elseif ($order['Nazwa_Statusu'] === 'Ukonczone') {
                                                echo "Zakończone";
                                            } elseif ($order['Nazwa_Statusu'] === 'Anulowane') {
                                                echo "Anulowane";
                                            } else {
                                                echo "Realizacja";
                                            }
                                            ?>
                                            <input type="hidden" name="delete_order_id" value="<?php echo htmlspecialchars($order['Id_Zlecenia']); ?>" />
                                            <input type="hidden" name="delete_order_name_status" value="<?php echo htmlspecialchars($order['Nazwa_Statusu']); ?>" />
                                            <input type="hidden" name="delete_order_id_status" value="<?php echo htmlspecialchars($order['Id_Statusu']); ?>" />
                                        </button>
                                    </form>
                                    <input type="hidden" class="page-dashboard-orders-user-single-serial_number" value="<?php echo htmlspecialchars($order['Numer_Seryjny']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-issue_desc" value="<?php echo htmlspecialchars($order['Opis_Problemu']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-status" value="<?php echo htmlspecialchars($order['Nazwa_Statusu']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-employee-name" value="<?php echo htmlspecialchars($order['Imie_Pracownika']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-employee-surname" value="<?php echo htmlspecialchars($order['Nazwisko_Pracownika']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-payment" value="<?php echo htmlspecialchars($order['Nazwa_Płatności']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-device-type" value="<?php echo htmlspecialchars($order['Nazwa_TypuUrządzenia']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-service-name" value="<?php echo htmlspecialchars($order['Nazwa_Usługi']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-service-price" value="<?php echo htmlspecialchars($order['Cena_Usługi']); ?>"/>
                                    <input type="hidden" class="page-dashboard-orders-user-single-service-desc" value="<?php echo htmlspecialchars($order['Opis_Usługi']); ?>"/>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Brak dodanych urządzeń przez klienta.</p>
                        <?php endif; ?>
                    </div>
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
        <div id="modal-user" class="modal-user">
            <div class="modal-content-user">
                <button id="close-modal-user" class="close-modal-user">&times;</button>
            </div>
        </div>

        <div id="modal-employee" class="modal-employee">
            <div class="modal-content-employee">
                <button id="close-modal-employee" class="close-modal-employee">&times;</button>
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
            const orderEmployeeName = orderRow.querySelector('.page-dashboard-orders-user-single-employee-name').value;
            const orderEmployeeSurname = orderRow.querySelector('.page-dashboard-orders-user-single-employee-surname').value;
            const orderPayment = orderRow.querySelector('.page-dashboard-orders-user-single-payment').value;
            const orderDeviceType = orderRow.querySelector('.page-dashboard-orders-user-single-device-type').value;
            const orderServiceName = orderRow.querySelector('.page-dashboard-orders-user-single-service-name').value;
            const orderServicePrice= orderRow.querySelector('.page-dashboard-orders-user-single-service-price').value;
            const orderServiceDesc= orderRow.querySelector('.page-dashboard-orders-user-single-service-desc').value;

            const modalContent = modalOrder.querySelector('.modal-content-order');
            modalContent.innerHTML = `
                <button id="close-modal-order" class="close-modal-order">&times;</button>
                <div class="model-content-order-head">
                    <h3 class="modal-content-order-title">Szczegóły Zamówienia</h3>
                </div>
<!--                <h2 class="modal-order-details-title">Szczegóły Zamówienia</h2>-->
                <div class="modal-order-details">
                    <div class="modal-order-detail">
                        <p>ID Zamówienia:</p>
                        <div>${orderId}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Urządzenie:</p>
                        <div>${orderDeviceType}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Nazwa:</p>
                        <div>${orderDevice}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Numer Seryjny:</p>
                        <div>${orderSerialNumber}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Opis Problemu:</p>
                        <div>${orderIssueDesc}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Status Zamówienia:</p>
                        <div>${orderStatus}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Metoda Płatności:</p>
                        <div>${orderPayment}</div>
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
                        <p>Serwisant:</p>
                        <div>${orderEmployeeName} ${orderEmployeeSurname}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Nazwa Usługi:</p>
                        <div>${orderServiceDesc}</div>
                    </div>
                    <div class="modal-order-detail">
                        <p>Cena Usługi:</p>
                        <div>${orderServicePrice} zł</div>
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

<!-- Uzytkownik-->

<script>
    const modalUser = document.getElementById('modal-user');
    const buttonsUser = document.querySelectorAll('.page-dashboard-admin-single-user');
    const closeModalUserButton = document.getElementById('close-modal-user');

    function closeModalUser() {
        modalUser.style.display = 'none';
    }

    buttonsUser.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const orderRow = button.closest('.page-dashboard-admin-single-user');
            const UserId = orderRow.querySelector('.page-dashboard-admin-user-id').value;
            const UserFirstname = orderRow.querySelector('.page-dashboard-admin-user-firstname').value;
            const UserLastname = orderRow.querySelector('.page-dashboard-admin-user-lastname').value;
            const UserEmail = orderRow.querySelector('.page-dashboard-admin-user-email').value;
            const UserPhone = orderRow.querySelector('.page-dashboard-admin-user-phone').value;
            const UserStreet = orderRow.querySelector('.page-dashboard-admin-user-street').value;
            const UserHouseNumber = orderRow.querySelector('.page-dashboard-admin-user-house-number').value;
            const UserPostCode = orderRow.querySelector('.page-dashboard-admin-user-post-code').value;
            const UserCity = orderRow.querySelector('.page-dashboard-admin-user-city').value;

            const modalContent = modalUser.querySelector('.modal-content-user');
            modalContent.innerHTML = `
                <button id="close-modal-user" class="close-modal-user">&times;</button>
                <div class="model-content-user-head">
                    <h3 class="modal-content-user-title">Dane Użytkownika</h3>
                </div>
                <div class="modal-user-details">
                    <div class="modal-user-detail">
                        <p>ID:</p>
                        <div>${UserId}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Imie:</p>
                        <div>${UserFirstname}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Nazwisko:</p>
                        <div>${UserLastname}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Email:</p>
                        <div>${UserEmail}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Telefon:</p>
                        <div>${UserPhone}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Miasto:</p>
                        <div>${UserCity}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Kod Pocztowy:</p>
                        <div>${UserPostCode}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Ulica:</p>
                        <div>${UserStreet}</div>
                    </div>
                    <div class="modal-user-detail">
                        <p>Numer Domu:</p>
                        <div>${UserHouseNumber}</div>
                    </div>
                </div>
            `;

            const closeButton = modalContent.querySelector('.close-modal-user');
            closeButton.addEventListener('click', closeModalUser);

            modalUser.style.display = 'flex';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modalUser) {
            closeModalUser();
        }
    });
</script>

<!-- Pracownicy-->

<script>
    const modalEmployee = document.getElementById('modal-employee');
    const buttonsEmployee = document.querySelectorAll('.page-dashboard-admin-single-employee');
    const closeModalEmployeeButton = document.getElementById('close-modal-employee');

    function closeModalEmployee() {
        modalEmployee.style.display = 'none';
    }

    buttonsEmployee.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const orderRow = button.closest('.page-dashboard-admin-single-employee');
            const EmployeeId = orderRow.querySelector('.page-dashboard-admin-employee-id').value;
            const EmployeeFirstname = orderRow.querySelector('.page-dashboard-admin-employee-firstname').value;
            const EmployeeLastname = orderRow.querySelector('.page-dashboard-admin-employee-lastname').value;
            const EmployeePositon = orderRow.querySelector('.page-dashboard-admin-employee-position').value;
            const EmployeeEmail = orderRow.querySelector('.page-dashboard-admin-employee-email').value;
            const EmployeePhone = orderRow.querySelector('.page-dashboard-admin-employee-phone').value;
            const EmployeeStreet = orderRow.querySelector('.page-dashboard-admin-employee-street').value;
            const EmployeeHouseNumber = orderRow.querySelector('.page-dashboard-admin-employee-house-number').value;
            const EmployeePostCode = orderRow.querySelector('.page-dashboard-admin-employee-post-code').value;
            const EmployeeCity = orderRow.querySelector('.page-dashboard-admin-employee-city').value;


            const modalContent = modalEmployee.querySelector('.modal-content-employee');
            modalContent.innerHTML = `
                <button id="close-modal-employee" class="close-modal-employee">&times;</button>
                <div class="model-content-employee-head">
                    <h3 class="modal-content-employee-title">Dane Pracownika</h3>
                </div>
                <div class="modal-employee-details">
                    <div class="modal-employee-detail">
                        <p>ID:</p>
                        <div>${EmployeeId}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Imie:</p>
                        <div>${EmployeeFirstname}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Nazwisko:</p>
                        <div>${EmployeeLastname}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Stanowisko:</p>
                        <div>${EmployeePositon}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Email:</p>
                        <div>${EmployeeEmail}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Numer Telefonu:</p>
                        <div>${EmployeePhone}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Miasto:</p>
                        <div>${EmployeeCity}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Kod Pocztowy:</p>
                        <div>${EmployeePostCode}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Ulica:</p>
                        <div>${EmployeeStreet}</div>
                    </div>
                    <div class="modal-employee-detail">
                        <p>Numer Domu:</p>
                        <div>${EmployeeHouseNumber}</div>
                    </div>
                </div>
            `;

            const closeButton = modalContent.querySelector('.close-modal-employee');
            closeButton.addEventListener('click', closeModalEmployee);

            modalEmployee.style.display = 'flex';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target === modalUser) {
            closeModalEmployee();
        }
    });
</script>