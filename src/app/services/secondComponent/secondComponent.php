<?php

require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function generateUniqueId() {
    return rand(1, 2147483647);
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

$sql = "SELECT Id_Pracownika, Imie, Nazwisko, Stanowisko FROM pracownicy";
$result = $conn->query($sql);

$options = '';
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $options .= "<option value='" . htmlspecialchars($row['Id_Pracownika']) . "'>" .
        htmlspecialchars($row['Imie']) . " " .
        htmlspecialchars($row['Nazwisko']) . " - " .
        htmlspecialchars($row['Stanowisko']) . "</option>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-service-phone'])) {
    $newPhoneMark = $_POST['phone_mark'] ?? null;
    $newPhoneModel = $_POST['phone_model'] ?? null;
    $newPhoneSerialNumber = $_POST['phone_serial_number'] ?? null;
    $newPhoneProblemDesc = $_POST['phone_problem_description'] ?? null;
    $newIdEmployee = $_POST['employee_id'] ?? null;

    $idDevice = generateUniqueId();
    $idOrder = generateUniqueId();
    $idTypeDevice = "2116961740";

    $newDateReception = (new DateTime())->format('Y-m-d');
    $idStatus = "2116961730";
    $idService = "2116961790";

    if ($newPhoneMark &&  $newPhoneModel && $newPhoneSerialNumber && $newPhoneProblemDesc) {

        try {
            $stmt = $conn->prepare("INSERT INTO urządzenia (Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu) VALUES (:id_device, :id_client, :id_type_device, :mark, :model, :serial_number, :description)");

            $stmt->bindParam(':id_device', $idDevice);
            $stmt->bindParam(':id_client',$ClientId );
            $stmt->bindParam(':id_type_device', $idTypeDevice);
            $stmt->bindParam(':mark', $newPhoneMark);
            $stmt->bindParam(':model', $newPhoneModel);
            $stmt->bindParam(':serial_number', $newPhoneSerialNumber);
            $stmt->bindParam(':description', $newPhoneProblemDesc);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO zlecenia (Id_Zlecenia, Id_Urządzenia, Id_Pracownika, Data_Przyjęcia, Opis_Problemu, Id_Statusu, Id_Usługi) VALUES (:id_order, :id_device, :id_employee, :date_reception, :issue_desc, :id_status, :id_service)");

            $stmt->bindParam(':id_order', $idOrder);
            $stmt->bindParam(':id_device',$idDevice );
            $stmt->bindParam(':id_employee', $newIdEmployee);
            $stmt->bindParam(':date_reception', $newDateReception);
            $stmt->bindParam(':issue_desc', $newPhoneProblemDesc);
            $stmt->bindParam(':id_status', $idStatus);
            $stmt->bindParam(':id_service', $idService);
            $stmt->execute();

            header('Location: ?page=dashboard');
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again.";
        }

    } else
    {
        echo "Nie udalo sie dodac nowej uslugi (Telefon)";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-service-laptop'])) {
    $newPhoneMark = $_POST['laptop_mark'] ?? null;
    $newPhoneModel = $_POST['laptop_model'] ?? null;
    $newPhoneSerialNumber = $_POST['laptop_serial_number'] ?? null;
    $newPhoneProblemDesc = $_POST['laptop_problem_description'] ?? null;
    $newIdEmployee = $_POST['employee_id_laptop'] ?? null;

    $idDevice = generateUniqueId();
    $idOrder = generateUniqueId();
    $idTypeDevice = "2116961742";

    $newDateReception = (new DateTime())->format('Y-m-d');
    $idStatus = "2116961730";
    $idService = "2116961797";

    if ($newPhoneMark &&  $newPhoneModel && $newPhoneSerialNumber && $newPhoneProblemDesc) {

        try {
            $stmt = $conn->prepare("INSERT INTO urządzenia (Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu) VALUES (:id_device, :id_client, :id_type_device, :mark, :model, :serial_number, :description)");

            $stmt->bindParam(':id_device', $idDevice);
            $stmt->bindParam(':id_client',$ClientId );
            $stmt->bindParam(':id_type_device', $idTypeDevice);
            $stmt->bindParam(':mark', $newPhoneMark);
            $stmt->bindParam(':model', $newPhoneModel);
            $stmt->bindParam(':serial_number', $newPhoneSerialNumber);
            $stmt->bindParam(':description', $newPhoneProblemDesc);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO zlecenia (Id_Zlecenia, Id_Urządzenia, Id_Pracownika, Data_Przyjęcia, Opis_Problemu, Id_Statusu, Id_Usługi) VALUES (:id_order, :id_device, :id_employee, :date_reception, :issue_desc, :id_status, :id_service)");

            $stmt->bindParam(':id_order', $idOrder);
            $stmt->bindParam(':id_device',$idDevice );
            $stmt->bindParam(':id_employee', $newIdEmployee);
            $stmt->bindParam(':date_reception', $newDateReception);
            $stmt->bindParam(':issue_desc', $newPhoneProblemDesc);
            $stmt->bindParam(':id_status', $idStatus);
            $stmt->bindParam(':id_service', $idService);
            $stmt->execute();

            header('Location: ?page=dashboard');
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again.";
        }

    } else
    {
        echo "Nie udalo sie dodac nowej uslugi (Laptop)";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-service-iphone'])) {
    $newPhoneModel = $_POST['iphone_model'] ?? null;
    $newPhoneSerialNumber = $_POST['iphone_serial_number'] ?? null;
    $newPhoneProblemDesc = $_POST['iphone_problem_description'] ?? null;
    $newIdEmployee = $_POST['iphone_employee_id'] ?? null;

    $idDevice = generateUniqueId();
    $idOrder = generateUniqueId();
    $idTypeDevice = "2116961741";
    $Iphone = "iPhone";

    $newDateReception = (new DateTime())->format('Y-m-d');
    $idStatus = "2116961730";
    $idService = "2116961793";

    if ($newPhoneModel && $newPhoneSerialNumber && $newPhoneProblemDesc) {

        try {
            $stmt = $conn->prepare("INSERT INTO urządzenia (Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu) VALUES (:id_device, :id_client, :id_type_device, :mark, :model, :serial_number, :description)");

            $stmt->bindParam(':id_device', $idDevice);
            $stmt->bindParam(':id_client',$ClientId );
            $stmt->bindParam(':id_type_device', $idTypeDevice);
            $stmt->bindParam(':mark', $Iphone);
            $stmt->bindParam(':model', $newPhoneModel);
            $stmt->bindParam(':serial_number', $newPhoneSerialNumber);
            $stmt->bindParam(':description', $newPhoneProblemDesc);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO zlecenia (Id_Zlecenia, Id_Urządzenia, Id_Pracownika, Data_Przyjęcia, Opis_Problemu, Id_Statusu, Id_Usługi) VALUES (:id_order, :id_device, :id_employee, :date_reception, :issue_desc, :id_status, :id_service)");

            $stmt->bindParam(':id_order', $idOrder);
            $stmt->bindParam(':id_device',$idDevice );
            $stmt->bindParam(':id_employee', $newIdEmployee);
            $stmt->bindParam(':date_reception', $newDateReception);
            $stmt->bindParam(':issue_desc', $newPhoneProblemDesc);
            $stmt->bindParam(':id_status', $idStatus);
            $stmt->bindParam(':id_service', $idService);
            $stmt->execute();

            header('Location: ?page=dashboard');
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again.";
        }

    } else
    {
        echo "Nie udalo sie dodac nowej uslugi (iPhone)";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-service-macbook'])) {
    $newPhoneModel = $_POST['macbook_model'] ?? null;
    $newPhoneSerialNumber = $_POST['macbook_serial_number'] ?? null;
    $newPhoneProblemDesc = $_POST['macbook_problem_description'] ?? null;
    $newIdEmployee = $_POST['macbook_employee_id'] ?? null;

    $idDevice = generateUniqueId();
    $idOrder = generateUniqueId();
    $idTypeDevice = "2116961744";
    $Iphone = "MacBook";

    $newDateReception = (new DateTime())->format('Y-m-d');
    $idStatus = "2116961730";
    $idService = "2116961793";

    if ($newPhoneModel && $newPhoneSerialNumber && $newPhoneProblemDesc) {

        try {
            $stmt = $conn->prepare("INSERT INTO urządzenia (Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu) VALUES (:id_device, :id_client, :id_type_device, :mark, :model, :serial_number, :description)");

            $stmt->bindParam(':id_device', $idDevice);
            $stmt->bindParam(':id_client',$ClientId );
            $stmt->bindParam(':id_type_device', $idTypeDevice);
            $stmt->bindParam(':mark', $Iphone);
            $stmt->bindParam(':model', $newPhoneModel);
            $stmt->bindParam(':serial_number', $newPhoneSerialNumber);
            $stmt->bindParam(':description', $newPhoneProblemDesc);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO zlecenia (Id_Zlecenia, Id_Urządzenia, Id_Pracownika, Data_Przyjęcia, Opis_Problemu, Id_Statusu, Id_Usługi) VALUES (:id_order, :id_device, :id_employee, :date_reception, :issue_desc, :id_status, :id_service)");

            $stmt->bindParam(':id_order', $idOrder);
            $stmt->bindParam(':id_device',$idDevice );
            $stmt->bindParam(':id_employee', $newIdEmployee);
            $stmt->bindParam(':date_reception', $newDateReception);
            $stmt->bindParam(':issue_desc', $newPhoneProblemDesc);
            $stmt->bindParam(':id_status', $idStatus);
            $stmt->bindParam(':id_service', $idService);
            $stmt->execute();

            header('Location: ?page=dashboard');
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again.";
        }

    } else
    {
        echo "Nie udalo sie dodac nowej uslugi (iPhone)";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-service-ipad'])) {
    $newPhoneModel = $_POST['ipad_model'] ?? null;
    $newPhoneSerialNumber = $_POST['ipad_serial_number'] ?? null;
    $newPhoneProblemDesc = $_POST['ipad_problem_description'] ?? null;
    $newIdEmployee = $_POST['ipad_employee_id'] ?? null;

    $idDevice = generateUniqueId();
    $idOrder = generateUniqueId();
    $idTypeDevice = "2116961745";
    $Iphone = "iPad";

    $newDateReception = (new DateTime())->format('Y-m-d');
    $idStatus = "2116961730";
    $idService = "2116961795";

    if ($newPhoneModel && $newPhoneSerialNumber && $newPhoneProblemDesc) {

        try {
            $stmt = $conn->prepare("INSERT INTO urządzenia (Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu) VALUES (:id_device, :id_client, :id_type_device, :mark, :model, :serial_number, :description)");

            $stmt->bindParam(':id_device', $idDevice);
            $stmt->bindParam(':id_client',$ClientId );
            $stmt->bindParam(':id_type_device', $idTypeDevice);
            $stmt->bindParam(':mark', $Iphone);
            $stmt->bindParam(':model', $newPhoneModel);
            $stmt->bindParam(':serial_number', $newPhoneSerialNumber);
            $stmt->bindParam(':description', $newPhoneProblemDesc);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO zlecenia (Id_Zlecenia, Id_Urządzenia, Id_Pracownika, Data_Przyjęcia, Opis_Problemu, Id_Statusu, Id_Usługi) VALUES (:id_order, :id_device, :id_employee, :date_reception, :issue_desc, :id_status, :id_service)");

            $stmt->bindParam(':id_order', $idOrder);
            $stmt->bindParam(':id_device',$idDevice );
            $stmt->bindParam(':id_employee', $newIdEmployee);
            $stmt->bindParam(':date_reception', $newDateReception);
            $stmt->bindParam(':issue_desc', $newPhoneProblemDesc);
            $stmt->bindParam(':id_status', $idStatus);
            $stmt->bindParam(':id_service', $idService);
            $stmt->execute();

            header('Location: ?page=dashboard');
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again.";
        }

    } else
    {
        echo "Nie udalo sie dodac nowej uslugi (iPad)";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add-service-tablet'])) {
    $newPhoneMark = $_POST['tablet_mark'] ?? null;
    $newPhoneModel = $_POST['tablet_model'] ?? null;
    $newPhoneSerialNumber = $_POST['tablet_serial_number'] ?? null;
    $newPhoneProblemDesc = $_POST['tablet_problem_description'] ?? null;
    $newIdEmployee = $_POST['employee_id_tablet'] ?? null;

    $idDevice = generateUniqueId();
    $idOrder = generateUniqueId();
    $idTypeDevice = "2116961746";

    $newDateReception = (new DateTime())->format('Y-m-d');
    $idStatus = "2116961730";
    $idService = "2116961794";

    if ($newPhoneMark &&  $newPhoneModel && $newPhoneSerialNumber && $newPhoneProblemDesc) {

        try {
            $stmt = $conn->prepare("INSERT INTO urządzenia (Id_Urządzenia, Id_Klienta, Id_TypuUrządzenia, Marka, Model, Numer_Seryjny, Opis_problemu) VALUES (:id_device, :id_client, :id_type_device, :mark, :model, :serial_number, :description)");

            $stmt->bindParam(':id_device', $idDevice);
            $stmt->bindParam(':id_client',$ClientId );
            $stmt->bindParam(':id_type_device', $idTypeDevice);
            $stmt->bindParam(':mark', $newPhoneMark);
            $stmt->bindParam(':model', $newPhoneModel);
            $stmt->bindParam(':serial_number', $newPhoneSerialNumber);
            $stmt->bindParam(':description', $newPhoneProblemDesc);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO zlecenia (Id_Zlecenia, Id_Urządzenia, Id_Pracownika, Data_Przyjęcia, Opis_Problemu, Id_Statusu, Id_Usługi) VALUES (:id_order, :id_device, :id_employee, :date_reception, :issue_desc, :id_status, :id_service)");

            $stmt->bindParam(':id_order', $idOrder);
            $stmt->bindParam(':id_device',$idDevice );
            $stmt->bindParam(':id_employee', $newIdEmployee);
            $stmt->bindParam(':date_reception', $newDateReception);
            $stmt->bindParam(':issue_desc', $newPhoneProblemDesc);
            $stmt->bindParam(':id_status', $idStatus);
            $stmt->bindParam(':id_service', $idService);
            $stmt->execute();

            header('Location: ?page=dashboard');
            exit();

        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "An error occurred. Please try again.";
        }

    } else
    {
        echo "Nie udalo sie dodac nowej uslugi (Tablet)";
    }
}



?>

<div class="page-services-second-back">
    <div class="page-services-second-container">
        <form method="post" class="page-services-second-grid">
            <div type="submit" class="page-services-second-card" data-service="phone">
                <img src="../../../../public/phone_fix.jpg" alt="Smartphone Repair" class="page-services-second-image">
                <div class="page-services-second-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M17 2H7c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H7V4h10v16z'/%3E%3C/svg%3E" alt="Phone icon">
                </div>
                <h3 class="page-services-second-title">Smartphone Repair</h3>
                <p class="page-services-second-description">Ideas it would brought city, been a concise upper office propitiously necessary though</p>
                <a href="#" class="page-services-second-read-more">Read More</a>
            </div>
            <div type="submit" class="page-services-second-card" data-service="laptop">
                <img src="../../../../public/laptop-repair.jpg" alt="Smartphone Repair" class="page-services-second-image">
                <div class="page-services-second-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M20 18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z'/%3E%3C/svg%3E" alt="Laptop icon">
                </div>
                <h3 class="page-services-second-title">Laptop Repair</h3>
                <p class="page-services-second-description">Ideas it would brought city, been a concise upper office propitiously necessary though</p>
                <a href="#" class="page-services-second-read-more">Read More</a>
            </div>
            <div type="submit" class="page-services-second-card" data-service="tablet">
                <img src="../../../../public/camera_fix.jpg" alt="Camera Repair" class="page-services-second-image">
                <div class="page-services-second-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM5 4h14v16H5V4zM12 19c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z'/%3E%3C/svg%3E" alt="Tablet icon">
                </div>
                <h3 class="page-services-second-title">Tablet Repair</h3>
                <p class="page-services-second-description">Chooses by not must structure to him all findings. Sitting into the and we he everyday</p>
                <a href="#" class="page-services-second-read-more">Read More</a>
            </div>
        </form>
        <form method="post" class="page-services-second-grid">
            <div type="submit" class="page-services-second-card" data-service="iphone">
                <img src="../../../../public/iphone_fix.jpg" alt="Camera Repair" class="page-services-second-image">
                <div class="page-services-second-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M17 2H7c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H7V4h10v16z'/%3E%3C/svg%3E" alt="Camera icon">
                </div>
                <h3 class="page-services-second-title">iPhone Repair</h3>
                <p class="page-services-second-description">Chooses by not must structure to him all findings. Sitting into the and we he everyday</p>
                <a href="#" class="page-services-second-read-more">Read More</a>
            </div>
            <div type="submit" class="page-services-second-card" data-service="macbook">
                <img src="../../../../public/macbook_fix.jpg" alt="Desktop Repair" class="page-services-second-image">
                <div class="page-services-second-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M20 18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z'/%3E%3C/svg%3E" alt="Desktop icon">
                </div>
                <h3 class="page-services-second-title">Macbook Repair</h3>
                <p class="page-services-second-description">Set power royal this boss it and of take all that, space hands and of the found in week</p>
                <a href="#" class="page-services-second-read-more">Read More</a>
            </div>
            <div type="submit" class="page-services-second-card" data-service="ipad">
                <img src="../../../../public/dektop_fix.jpg" alt="Desktop Repair" class="page-services-second-image">
                <div class="page-services-second-icon">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19 2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM5 4h14v16H5V4zM12 19c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z'/%3E%3C/svg%3E" alt="Tablet icon">
                </div>
                <h3 class="page-services-second-title">iPad Repair</h3>
                <p class="page-services-second-description">Set power royal this boss it and of take all that, space hands and of the found in week</p>
                <a href="#" class="page-services-second-read-more">Read More</a>
            </div>
        </form>
        <div id="modal-service" class="modal-service">
            <div class="modal-content-service">
                <button id="close-modal-service" class="close-modal-service">&times;</button>

            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modal-service');
    const buttons = document.querySelectorAll('.page-services-second-card');

    function closeModal() {
        modal.style.display = 'none';
    }

    buttons.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const service = button.getAttribute('data-service');

            const modalContent = modal.querySelector('.modal-content-service');

            modalContent.innerHTML = `
                <button id="close-modal-service" class="close-modal-service">&times;</button>
            `;

            if (service === 'phone') {
                modalContent.innerHTML += `
                    <h2>Serwis Telefonu</h2>
                    <form method="post">
                        <div class="page-service-add-service">
                            <div class="page-service-add-service-data">
                                <h5>Marka Telefonu</h5>
                                <input type="text" name="phone_mark" placeholder="Marka Telefonu" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Model Telefonu</h5>
                                <input type="text" name="phone_model" placeholder="Model Telefonu" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Numer Seryjny</h5>
                                <input type="text" name="phone_serial_number" placeholder="Numer Seryjny" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Opis problemu</h5>
                                <textarea name="phone_problem_description" placeholder="Opis problemu" required></textarea>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Wybierz pracownika</h5>
                                <select name="employee_id" required>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add-service-phone" class="page-service-add-service-data-button">Zapisz zmiany</button>
                    </form>
                `;
            }
            else if (service === 'ipad') {
                modalContent.innerHTML += `
                    <h2>Serwis iPad</h2>
                    <form method="post">
                        <div class="page-service-add-service">
                            <div class="page-service-add-service-data">
                                <h5>Model iPad'a</h5>
                                <input type="text" name="ipad_model" placeholder="Model iPada" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Numer Seryjny</h5>
                                <input type="text" name="ipad_serial_number" placeholder="Numer Seryjny" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Opis problemu</h5>
                                <textarea name="ipad_problem_description" placeholder="Opis problemu" required></textarea>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Wybierz pracownika</h5>
                                <select name="ipad_employee_id" required>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add-service-ipad" class="page-service-add-service-data-button">Zapisz zmiany</button>
                    </form>
                `;
            }
            else if (service === 'tablet') {
                modalContent.innerHTML += `
                    <h2>Serwis Tabletu</h2>
                    <form method="post">
                        <div class="page-service-add-service">
                            <div class="page-service-add-service-data">
                                <h5>Marka Tabletu</h5>
                                <input type="text" name="tablet_mark" placeholder="Marka Tabletu" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Model Tabletu</h5>
                                <input type="text" name="tablet_model" placeholder="Model Tabletu" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Numer Seryjny</h5>
                                <input type="text" name="tablet_serial_number" placeholder="Numer Seryjny" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Opis problemu</h5>
                                <textarea name="tablet_problem_description" placeholder="Opis problemu" required></textarea>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Wybierz pracownika</h5>
                                <select name="employee_id_tablet" required>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add-service-tablet" class="page-service-add-service-data-button">Zapisz zmiany</button>
                    </form>
                `;
            }
            else if (service === 'laptop') {
                modalContent.innerHTML += `
                    <h2>Serwis Laptopu</h2>
                    <form method="post">
                        <div class="page-service-add-service">
                            <div class="page-service-add-service-data">
                                <h5>Marka Laptopu</h5>
                                <input type="text" name="laptop_mark" placeholder="Marka Laptopu" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Model Laptopu</h5>
                                <input type="text" name="laptop_model" placeholder="Model Laptopu" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Numer Seryjny</h5>
                                <input type="text" name="laptop_serial_number" placeholder="Numer Seryjny" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Opis problemu</h5>
                                <textarea name="laptop_problem_description" placeholder="Opis problemu" required></textarea>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Wybierz pracownika</h5>
                                <select name="employee_id_laptop" required>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add-service-laptop" class="page-service-add-service-data-button">Zapisz zmiany</button>
                    </form>
                `;
            }
            else if (service === 'macbook') {
                modalContent.innerHTML += `
                    <h2>Serwis MacBook</h2>
                    <form method="post">
                        <div class="page-service-add-service">
                            <div class="page-service-add-service-data">
                                <h5>Model MacBook'a</h5>
                                <input type="text" name="macbook_model" placeholder="Model MacBook'a" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Numer Seryjny</h5>
                                <input type="text" name="macbook_serial_number" placeholder="Numer Seryjny" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Opis problemu</h5>
                                <textarea name="macbook_problem_description" placeholder="Opis problemu" required></textarea>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Wybierz pracownika</h5>
                                <select name="macbook_employee_id" required>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add-service-macbook" class="page-service-add-service-data-button">Zapisz zmiany</button>
                    </form>
                `;
            }
            else if (service === 'iphone') {
                modalContent.innerHTML += `
                    <h2>Serwis iPhone</h2>
                    <form method="post">
                        <div class="page-service-add-service">
                            <div class="page-service-add-service-data">
                                <h5>Model iPhone'a</h5>
                                <input type="text" name="iphone_model" placeholder="Model iPhone'a" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Numer Seryjny</h5>
                                <input type="text" name="iphone_serial_number" placeholder="Numer Seryjny" required>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Opis problemu</h5>
                                <textarea name="iphone_problem_description" placeholder="Opis problemu" required></textarea>
                            </div>
                            <div class="page-service-add-service-data">
                                <h5>Wybierz pracownika</h5>
                                <select name="iphone_employee_id" required>
                                    <?php echo $options; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="add-service-iphone" class="page-service-add-service-data-button">Zapisz zmiany</button>
                    </form>
                `;
            }

            const closeModalButton = modal.querySelector('#close-modal-service');
            closeModalButton.addEventListener('click', closeModal);

            modal.style.display = 'flex';
        });
    });
</script>
