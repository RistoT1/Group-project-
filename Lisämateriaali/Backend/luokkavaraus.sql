-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 12.11.2025 klo 05:45
-- Palvelimen versio: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luokkavaraus`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `kayttajat`
--

CREATE TABLE `kayttajat` (
  `KayttajaID` int(11) NOT NULL,
  `Nimi` varchar(50) DEFAULT NULL,
  `Sukunimi` varchar(50) DEFAULT NULL,
  `SalasanaHash` varchar(100) DEFAULT NULL,
  `Sähköposti` varchar(100) DEFAULT NULL,
  `Puhelinnumero` varchar(20) DEFAULT NULL,
  `LuomisPvm` datetime DEFAULT current_timestamp(),
  `MuokkausPvm` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Rooli` enum('opettaja','ylläpitäjä') DEFAULT 'opettaja'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `kayttajat`
--

INSERT INTO `kayttajat` (`KayttajaID`, `Nimi`, `Sukunimi`, `SalasanaHash`, `Sähköposti`, `Puhelinnumero`, `LuomisPvm`, `MuokkausPvm`, `Rooli`) VALUES
(2, 'Laura', 'Korhonen', 'hash67890', 'laura.korhonen@example.com', '0507654321', '2025-11-04 12:16:16', '2025-11-04 08:16:16', 'opettaja'),
(3, 'Antti', 'Virtanen', 'hashabcdef', 'antti.virtanen@example.com', '0419876543', '2025-11-04 12:16:16', '2025-11-04 08:16:16', 'opettaja'),
(4, 'John Doe', NULL, '$2y$10$zg2HVYwBynO/qLZPJ972o.PAkHCotGqaezrFXxfzl4O2SJFAWaFMa', 'john@example.com', '0909090', NULL, '2025-11-06 09:15:33', 'ylläpitäjä'),
(12, 'John Doe', NULL, '$2y$10$meqxILcWapNmvEB7ByIlqeN4nEQv77PZFuC91Npz8w76Q5ih9AgkW', 'john@exaamle.com', '09090090', '2025-11-06 11:42:18', '2025-11-06 07:42:18', 'opettaja'),
(14, 'risto1', NULL, '$2y$10$q9TOVvINDt/pD7qCHOQvlOkb2ODYLtSkoD4wLMztoUJgTu353gHQ.', 'rt', '4094040', '2025-11-06 14:45:01', '2025-11-06 14:06:01', 'ylläpitäjä');

-- --------------------------------------------------------

--
-- Rakenne taululle `luokat`
--

CREATE TABLE `luokat` (
  `LuokkaID` int(11) NOT NULL,
  `Nimi` varchar(50) DEFAULT NULL,
  `Varusteet` varchar(255) DEFAULT NULL,
  `Kapasiteetti` int(11) DEFAULT NULL,
  `Sijainti` varchar(100) DEFAULT NULL,
  `Tila` enum('aktiivinen','poissa_käytöstä','poistettu') DEFAULT 'aktiivinen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `luokat`
--

INSERT INTO `luokat` (`LuokkaID`, `Nimi`, `Varusteet`, `Kapasiteetti`, `Sijainti`, `Tila`) VALUES
(1, 'Luokka A101', 'Projektori, valkotaulu, tietokoneet', 30, 'A101', 'aktiivinen'),
(2, 'Luokka B202', 'Projektori, valkotaulu', 25, 'B202', 'aktiivinen'),
(3, 'Luokka C303', 'Tietokoneet, 3D-tulostin', 20, 'C303', 'aktiivinen'),
(4, 'Luokka D404', 'Laboratoriovarusteet, kemikaalit', 15, 'D404', 'aktiivinen'),
(5, 'Luokka E105', 'Valkotaulu, äänentoisto', 40, 'E105', 'poissa_käytöstä'),
(6, 'Luokka F206', 'Projektori, tietokoneet', 35, 'F206', 'aktiivinen'),
(7, 'Luokka G307', 'Laboratoriovarusteet, tietokoneet', 18, 'G307', 'aktiivinen'),
(8, 'Luokka123', 'Mummon telamiinat', 32, 'M123', 'aktiivinen');

-- --------------------------------------------------------

--
-- Rakenne taululle `varaukset`
--

CREATE TABLE `varaukset` (
  `VarausID` int(11) NOT NULL,
  `KayttajaID` int(11) NOT NULL,
  `LuokkaID` int(11) NOT NULL,
  `Paivamaara` date NOT NULL,
  `AloitusAika` time NOT NULL,
  `LopetusAika` time NOT NULL,
  `Tarkoitus` varchar(100) DEFAULT NULL,
  `Tila` enum('vapaa','varattu','vahvistettu','peruttu') DEFAULT 'varattu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `varaukset`
--

INSERT INTO `varaukset` (`VarausID`, `KayttajaID`, `LuokkaID`, `Paivamaara`, `AloitusAika`, `LopetusAika`, `Tarkoitus`, `Tila`) VALUES
(5, 2, 2, '2025-11-11', '11:20:00', '12:00:00', 'LEipa', 'peruttu'),
(8, 2, 2, '2025-11-11', '11:20:00', '12:00:00', 'LEipa', 'varattu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kayttajat`
--
ALTER TABLE `kayttajat`
  ADD PRIMARY KEY (`KayttajaID`),
  ADD UNIQUE KEY `Sähköposti` (`Sähköposti`),
  ADD UNIQUE KEY `Puhelinnumero` (`Puhelinnumero`);

--
-- Indexes for table `luokat`
--
ALTER TABLE `luokat`
  ADD PRIMARY KEY (`LuokkaID`);

--
-- Indexes for table `varaukset`
--
ALTER TABLE `varaukset`
  ADD PRIMARY KEY (`VarausID`),
  ADD KEY `KayttajaID` (`KayttajaID`),
  ADD KEY `LuokkaID` (`LuokkaID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kayttajat`
--
ALTER TABLE `kayttajat`
  MODIFY `KayttajaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `luokat`
--
ALTER TABLE `luokat`
  MODIFY `LuokkaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `varaukset`
--
ALTER TABLE `varaukset`
  MODIFY `VarausID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `varaukset`
--
ALTER TABLE `varaukset`
  ADD CONSTRAINT `varaukset_ibfk_1` FOREIGN KEY (`KayttajaID`) REFERENCES `kayttajat` (`KayttajaID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `varaukset_ibfk_2` FOREIGN KEY (`LuokkaID`) REFERENCES `luokat` (`LuokkaID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
