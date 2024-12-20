-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 04:01 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techcare`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `adresy`
--

CREATE TABLE `adresy` (
  `Id_Adresu` int(11) NOT NULL,
  `Ulica` varchar(255) DEFAULT NULL,
  `Numer_domu` varchar(50) DEFAULT NULL,
  `Kod_Pocztowy` varchar(20) DEFAULT NULL,
  `Miasto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adresy`
--

INSERT INTO `adresy` (`Id_Adresu`, `Ulica`, `Numer_domu`, `Kod_Pocztowy`, `Miasto`) VALUES
(12, 'Zeromskiego', '78', '90-892', 'Łódź'),
(13, 'Gdańska', '8', '90-892', 'Łódź'),
(492702530, '', '', '', ''),
(862961103, 'Długa', '25', '92-536', 'Zgierz'),
(1762729879, 'Radwanska', '42', '90-100', 'Lodz'),
(1988141733, '', '', '', ''),
(1988141734, '', '', '', ''),
(1988141735, '', '', '', ''),
(1988141736, '', '', '', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `klienci`
--

CREATE TABLE `klienci` (
  `Id_Klienta` int(11) NOT NULL,
  `Imie` varchar(100) DEFAULT NULL,
  `Nazwisko` varchar(100) DEFAULT NULL,
  `Id_Kontaktu` int(11) DEFAULT NULL,
  `Id_Adresu` int(11) DEFAULT NULL,
  `Zdjecie` blob DEFAULT NULL,
  `Id_Logowania` int(11) DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klienci`
--

INSERT INTO `klienci` (`Id_Klienta`, `Imie`, `Nazwisko`, `Id_Kontaktu`, `Id_Adresu`, `Zdjecie`, `Id_Logowania`, `isAdmin`) VALUES
(10, 'Daniel', 'Karolak', 1238844086, 1762729879, '', 2085883300, 1),
(11, 'Jan', 'Kowalski', 108579517, 862961103, '', 153603659, 0),
(2116961759, 'Dawid', 'Kubacki', 1945275772, 1988141733, '', 2085883301, 0),
(2116961760, 'Jan', 'Ziemianski', 1945275773, 1988141734, '', 2085883302, 0),
(2116961761, 'Dawid', 'Kowalski', 1945275774, 1988141735, '', 2085883303, 0),
(2116961762, 'Justyna', 'Karolak', 1945275775, 1988141736, '', 2085883304, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kontakty`
--

CREATE TABLE `kontakty` (
  `Id_Kontaktu` int(11) NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `NumerTelefonu` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontakty`
--

INSERT INTO `kontakty` (`Id_Kontaktu`, `Email`, `NumerTelefonu`) VALUES
(134, 'nowak.techcare.test.com', '999888777'),
(135, 'król.techcare@test.com', '789345671'),
(108579517, 'kowalski@test.com', '111222333'),
(1238844086, 'danielkarolak@test.com', '987654321'),
(1489894908, 'nowak@test.com', ''),
(1945275772, 'kuback@test.com', ''),
(1945275773, 'ziemianski@test.com', ''),
(1945275774, 'dkowalski@test.com', ''),
(1945275775, 'jkarolak@test.com', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logowanie`
--

CREATE TABLE `logowanie` (
  `Id_Logowania` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Haslo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logowanie`
--

INSERT INTO `logowanie` (`Id_Logowania`, `Email`, `Haslo`) VALUES
(153603659, 'kowalski@test.com', '$2y$10$UhEu34LOSrcbxIv4U2/J9OICKjT30oLRiNbLrpdTHAVynQ7ZDXd6W'),
(1664131799, 'nowak@test.com', '$2y$10$M/mDgNp7B/BIS.B68JM6cO.5Kqm6MSsb9u3WKxB0YBxUXDZvH1YjG'),
(2085883300, 'danielkarolak@test.com', '$2y$10$5XIMAze8nBQFVkFpal9QXuhNY5BkyYVot.fPf5DAkpf0PlNo2Y2bm'),
(2085883301, 'kuback@test.com', '$2y$10$oN3Q9z3ihKbBV5nTwAtLFecTpE2RDwf9Uf9eignUZBqwmNooBk1Pq'),
(2085883302, 'ziemianski@test.com', '$2y$10$yBtEPLKxT2eh6dhSJnZvc.jwUxVD8xErm0VXvvwBv4rkhCiZOwHra'),
(2085883303, 'dkowalski@test.com', '$2y$10$9nVHtv2pO6KQWBbrDnrOH.VzmJvk3wQToRhZkBKE4sug2D8SfZtJ2'),
(2085883304, 'jkarolak@test.com', '$2y$10$Ut3DghgzpYdaZA2ydLmiCuViwfT.WzfBQ8ek1Z5Yjl/yn8CusBv5a');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `Id_Pracownika` int(11) NOT NULL,
  `Imie` varchar(100) DEFAULT NULL,
  `Nazwisko` varchar(100) DEFAULT NULL,
  `Stanowisko` varchar(100) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Telefon` varchar(20) DEFAULT NULL,
  `Id_Kontaktu` int(11) DEFAULT NULL,
  `Id_Adresu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pracownicy`
--

INSERT INTO `pracownicy` (`Id_Pracownika`, `Imie`, `Nazwisko`, `Stanowisko`, `Email`, `Telefon`, `Id_Kontaktu`, `Id_Adresu`) VALUES
(2116961700, 'Adam', 'Nowak', 'Serwisant komputerowy', 'nowak.techcare.test.com', '999888777', 134, 13),
(2116961701, 'Damian', 'Król', 'Serwisant telefonów', 'król.techcare@test.com', '789345671', 135, 12);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `płatność`
--

CREATE TABLE `płatność` (
  `Id_Płatności` int(11) NOT NULL,
  `Nazwa_Płatności` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `płatność`
--

INSERT INTO `płatność` (`Id_Płatności`, `Nazwa_Płatności`) VALUES
(2116216192, 'Brak'),
(2116216193, 'Blik'),
(2116216194, 'Przelew Bankowy'),
(2116216195, 'Karta Płatnicza');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `status`
--

CREATE TABLE `status` (
  `Id_Statusu` int(11) NOT NULL,
  `Nazwa` enum('Nowe','W Trakcie Realizacji','Ukonczone','Zaplacone','Anulowane','Nie Zaplacone') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`Id_Statusu`, `Nazwa`) VALUES
(2116961730, 'Nowe'),
(2116961731, 'W Trakcie Realizacji'),
(2116961732, 'Ukonczone'),
(2116961733, 'Zaplacone'),
(2116961734, 'Anulowane'),
(2116961735, 'Nie Zaplacone');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typurządzenia`
--

CREATE TABLE `typurządzenia` (
  `Id_TypuUrządzenia` int(11) NOT NULL,
  `Nazwa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `typurządzenia`
--

INSERT INTO `typurządzenia` (`Id_TypuUrządzenia`, `Nazwa`) VALUES
(2116961740, 'Smartfon'),
(2116961741, 'iPhone'),
(2116961742, 'Laptop'),
(2116961744, 'MacBook'),
(2116961745, 'iPad'),
(2116961746, 'Tablet');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `urządzenia`
--

CREATE TABLE `urządzenia` (
  `Id_Urządzenia` int(11) NOT NULL,
  `Id_Klienta` int(11) DEFAULT NULL,
  `Id_TypuUrządzenia` int(11) DEFAULT NULL,
  `Marka` varchar(100) DEFAULT NULL,
  `Model` varchar(100) DEFAULT NULL,
  `Numer_Seryjny` varchar(100) DEFAULT NULL,
  `Opis_problemu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `urządzenia`
--

INSERT INTO `urządzenia` (`Id_Urządzenia`, `Id_Klienta`, `Id_TypuUrządzenia`, `Marka`, `Model`, `Numer_Seryjny`, `Opis_problemu`) VALUES
(2134069185, 10, 2116961744, 'MacBook', 'M10', '543421', 'askidhk'),
(2134069187, 10, 2116961742, 'Lenovo', 'Legion Y10', '30192321', 'jjjjjjjjj'),
(2134069191, 10, 2116961746, 'yyyyy', 'as', 'dsadas', 'dsadsa'),
(2134069193, 10, 2116961740, 'Dodge', 'x6 pro', '6666666666', 'wwwwwwwwwww'),
(2134069195, 10, 2116961741, 'iPhone', '8', '222222222', 'aaaaaaa'),
(2134069198, 10, 2116961742, 'HP2', 'Pavilion2', '3213212', 'tttttttaa2'),
(2134069203, 10, 2116961744, 'MacBook1', 'M101', '31783211', 'Nie uruchamia sie1'),
(2134069204, 10, 2116961740, 'test', 'testowy', '321392183', 'saw');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `usługi`
--

CREATE TABLE `usługi` (
  `Id_Usługi` int(11) NOT NULL,
  `Nazwa` varchar(100) DEFAULT NULL,
  `Opis` text DEFAULT NULL,
  `Cena` decimal(10,2) DEFAULT NULL,
  `Id_TypuUrządzenia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usługi`
--

INSERT INTO `usługi` (`Id_Usługi`, `Nazwa`, `Opis`, `Cena`, `Id_TypuUrządzenia`) VALUES
(2116961790, 'Naprawa Telefonu', 'Naprawa Telefonu z Androidem', 300.00, 2116961740),
(2116961791, 'Wymiana Baterii', 'Wymiana baterii w twoim telefonie', 600.00, 2116961740),
(2116961792, 'Wymiana Baterii w Laptopie', 'Wymiana Baterii w Twoim Laptopie', 700.00, 2116961742),
(2116961793, 'Naprawa Iphone', 'Naprawa Telefonu z IOS', 400.00, 2116961741),
(2116961794, 'Naprawa Tabletu', 'Diagnoza oraz naprawa tabletu', 400.00, 2116961746),
(2116961795, 'Naprawa iPad', 'Diagnoza oraz naprawa twojego iPada', 500.00, 2116961745),
(2116961796, 'Naprawa MacBook', 'Diagnoza oraz naprawa twojego MacBook\'a', 650.00, 2116961744),
(2116961797, 'Naprawa Laptopu', 'Diagnoza oraz naprawa twojego laptopu', 700.00, 2116961742);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zlecenia`
--

CREATE TABLE `zlecenia` (
  `Id_Zlecenia` int(11) NOT NULL,
  `Id_Urządzenia` int(11) DEFAULT NULL,
  `Id_Pracownika` int(11) DEFAULT NULL,
  `Data_Przyjęcia` date DEFAULT NULL,
  `Opis_Problemu` text DEFAULT NULL,
  `Id_Statusu` int(11) DEFAULT NULL,
  `Data_Zakończenia` date DEFAULT NULL,
  `Id_Usługi` int(11) DEFAULT NULL,
  `Id_Płatności` int(11) DEFAULT NULL,
  `Cena_Zlecenia` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zlecenia`
--

INSERT INTO `zlecenia` (`Id_Zlecenia`, `Id_Urządzenia`, `Id_Pracownika`, `Data_Przyjęcia`, `Opis_Problemu`, `Id_Statusu`, `Data_Zakończenia`, `Id_Usługi`, `Id_Płatności`, `Cena_Zlecenia`) VALUES
(2116961779, 2134069185, 2116961700, '2024-12-19', 'Naprawione', 2116961732, '2024-12-19', 2116961796, 2116216193, 650.00),
(2116961781, 2134069187, 2116961700, '2024-12-19', '', 2116961733, NULL, 2116961797, 2116216192, 700.00),
(2116961787, 2134069193, 2116961700, '2024-12-19', '', 2116961732, '2024-12-02', 2116961791, 2116216193, 600.00),
(2116961789, 2134069195, 2116961701, '2024-12-19', '', 2116961734, NULL, 2116961790, 2116216193, 400.00),
(2116961792, 2134069198, 2116961701, '2024-12-20', '', 2116961734, NULL, 2116961793, 2116216195, 700.00),
(2116961797, 2134069203, 2116961701, '2024-12-20', 'Wymieniono zasilacz oraz plyte', 2116961731, '2024-12-23', 2116961796, 2116216193, 650.00),
(2116961798, 2134069204, 2116961700, '2024-12-20', '', 2116961735, NULL, 2116961790, 2116216192, 300.00);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `adresy`
--
ALTER TABLE `adresy`
  ADD PRIMARY KEY (`Id_Adresu`);

--
-- Indeksy dla tabeli `klienci`
--
ALTER TABLE `klienci`
  ADD PRIMARY KEY (`Id_Klienta`),
  ADD KEY `Id_Kontaktu` (`Id_Kontaktu`),
  ADD KEY `Id_Adresu` (`Id_Adresu`),
  ADD KEY `Id_Logowania` (`Id_Logowania`);

--
-- Indeksy dla tabeli `kontakty`
--
ALTER TABLE `kontakty`
  ADD PRIMARY KEY (`Id_Kontaktu`);

--
-- Indeksy dla tabeli `logowanie`
--
ALTER TABLE `logowanie`
  ADD PRIMARY KEY (`Id_Logowania`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`Id_Pracownika`),
  ADD KEY `fk_pracownicy_kontakty` (`Id_Kontaktu`),
  ADD KEY `fk_pracownicy_adresy` (`Id_Adresu`);

--
-- Indeksy dla tabeli `płatność`
--
ALTER TABLE `płatność`
  ADD PRIMARY KEY (`Id_Płatności`);

--
-- Indeksy dla tabeli `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`Id_Statusu`);

--
-- Indeksy dla tabeli `typurządzenia`
--
ALTER TABLE `typurządzenia`
  ADD PRIMARY KEY (`Id_TypuUrządzenia`);

--
-- Indeksy dla tabeli `urządzenia`
--
ALTER TABLE `urządzenia`
  ADD PRIMARY KEY (`Id_Urządzenia`),
  ADD KEY `Id_Klienta` (`Id_Klienta`),
  ADD KEY `Id_TypuUrządzenia` (`Id_TypuUrządzenia`);

--
-- Indeksy dla tabeli `usługi`
--
ALTER TABLE `usługi`
  ADD PRIMARY KEY (`Id_Usługi`),
  ADD KEY `Id_TypuUrządzenia` (`Id_TypuUrządzenia`);

--
-- Indeksy dla tabeli `zlecenia`
--
ALTER TABLE `zlecenia`
  ADD PRIMARY KEY (`Id_Zlecenia`),
  ADD KEY `Id_Urządzenia` (`Id_Urządzenia`),
  ADD KEY `Id_Pracownika` (`Id_Pracownika`),
  ADD KEY `Id_Statusu` (`Id_Statusu`),
  ADD KEY `Id_Usługi` (`Id_Usługi`),
  ADD KEY `Id_Płatności` (`Id_Płatności`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresy`
--
ALTER TABLE `adresy`
  MODIFY `Id_Adresu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1988141737;

--
-- AUTO_INCREMENT for table `klienci`
--
ALTER TABLE `klienci`
  MODIFY `Id_Klienta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116961763;

--
-- AUTO_INCREMENT for table `kontakty`
--
ALTER TABLE `kontakty`
  MODIFY `Id_Kontaktu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1945275776;

--
-- AUTO_INCREMENT for table `logowanie`
--
ALTER TABLE `logowanie`
  MODIFY `Id_Logowania` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2085883305;

--
-- AUTO_INCREMENT for table `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `Id_Pracownika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116961702;

--
-- AUTO_INCREMENT for table `płatność`
--
ALTER TABLE `płatność`
  MODIFY `Id_Płatności` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116216199;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `Id_Statusu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116961736;

--
-- AUTO_INCREMENT for table `typurządzenia`
--
ALTER TABLE `typurządzenia`
  MODIFY `Id_TypuUrządzenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116961747;

--
-- AUTO_INCREMENT for table `urządzenia`
--
ALTER TABLE `urządzenia`
  MODIFY `Id_Urządzenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2134069205;

--
-- AUTO_INCREMENT for table `usługi`
--
ALTER TABLE `usługi`
  MODIFY `Id_Usługi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116961798;

--
-- AUTO_INCREMENT for table `zlecenia`
--
ALTER TABLE `zlecenia`
  MODIFY `Id_Zlecenia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2116961799;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `klienci`
--
ALTER TABLE `klienci`
  ADD CONSTRAINT `klienci_ibfk_1` FOREIGN KEY (`Id_Kontaktu`) REFERENCES `kontakty` (`Id_Kontaktu`),
  ADD CONSTRAINT `klienci_ibfk_2` FOREIGN KEY (`Id_Adresu`) REFERENCES `adresy` (`Id_Adresu`),
  ADD CONSTRAINT `klienci_ibfk_3` FOREIGN KEY (`Id_Logowania`) REFERENCES `logowanie` (`Id_Logowania`);

--
-- Constraints for table `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD CONSTRAINT `fk_pracownicy_adresy` FOREIGN KEY (`Id_Adresu`) REFERENCES `adresy` (`Id_Adresu`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pracownicy_kontakty` FOREIGN KEY (`Id_Kontaktu`) REFERENCES `kontakty` (`Id_Kontaktu`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `urządzenia`
--
ALTER TABLE `urządzenia`
  ADD CONSTRAINT `urządzenia_ibfk_1` FOREIGN KEY (`Id_Klienta`) REFERENCES `klienci` (`Id_Klienta`),
  ADD CONSTRAINT `urządzenia_ibfk_2` FOREIGN KEY (`Id_TypuUrządzenia`) REFERENCES `typurządzenia` (`Id_TypuUrządzenia`);

--
-- Constraints for table `usługi`
--
ALTER TABLE `usługi`
  ADD CONSTRAINT `usługi_ibfk_1` FOREIGN KEY (`Id_TypuUrządzenia`) REFERENCES `typurządzenia` (`Id_TypuUrządzenia`);

--
-- Constraints for table `zlecenia`
--
ALTER TABLE `zlecenia`
  ADD CONSTRAINT `fk_zlecenia_platnosc` FOREIGN KEY (`Id_Płatności`) REFERENCES `płatność` (`Id_Płatności`),
  ADD CONSTRAINT `zlecenia_ibfk_1` FOREIGN KEY (`Id_Urządzenia`) REFERENCES `urządzenia` (`Id_Urządzenia`),
  ADD CONSTRAINT `zlecenia_ibfk_2` FOREIGN KEY (`Id_Pracownika`) REFERENCES `pracownicy` (`Id_Pracownika`),
  ADD CONSTRAINT `zlecenia_ibfk_3` FOREIGN KEY (`Id_Statusu`) REFERENCES `status` (`Id_Statusu`),
  ADD CONSTRAINT `zlecenia_ibfk_4` FOREIGN KEY (`Id_Usługi`) REFERENCES `usługi` (`Id_Usługi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
