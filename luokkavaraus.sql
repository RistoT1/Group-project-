-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 04.11.2025 klo 11:16
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
  `LuomisPvm` datetime DEFAULT NULL,
  `MuokkausPvm` datetime DEFAULT NULL,
  `Rooli` enum('opettaja','ylläpitäjä') DEFAULT 'opettaja'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `kayttajat`
--

INSERT INTO `kayttajat` (`KayttajaID`, `Nimi`, `Sukunimi`, `SalasanaHash`, `Sähköposti`, `Puhelinnumero`, `LuomisPvm`, `MuokkausPvm`, `Rooli`) VALUES
(2, 'Laura', 'Korhonen', 'hash67890', 'laura.korhonen@example.com', '0507654321', '2025-11-04 12:16:16', '2025-11-04 12:16:16', 'opettaja'),
(3, 'Antti', 'Virtanen', 'hashabcdef', 'antti.virtanen@example.com', '0419876543', '2025-11-04 12:16:16', '2025-11-04 12:16:16', 'opettaja');

-- --------------------------------------------------------

--
-- Rakenne taululle `luokat`
--

CREATE TABLE `luokat` (
  `LuokkaID` int(11) NOT NULL,
  `Nimi` varchar(50) DEFAULT NULL,
  `Varusteet` varchar(50) DEFAULT NULL,
  `Kapasiteetti` int(11) DEFAULT NULL,
  `Sijainti` varchar(20) DEFAULT NULL,
  `Tila` enum('aktiivinen','poissa_käytöstä','poistettu') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `varattavatajat`
--

CREATE TABLE `varattavatajat` (
  `AikaID` int(11) NOT NULL,
  `LuokkaID` int(11) DEFAULT NULL,
  `AloitusAika` datetime DEFAULT NULL,
  `LopetusAika` datetime DEFAULT NULL,
  `Tila` enum('varattu','vahvistettu','peruttu') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `varaukset`
--

CREATE TABLE `varaukset` (
  `VarausID` int(11) NOT NULL,
  `KayttajaID` int(11) DEFAULT NULL,
  `AikaID` int(11) DEFAULT NULL,
  `Tarkoitus` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kayttajat`
--
ALTER TABLE `kayttajat`
  ADD PRIMARY KEY (`KayttajaID`);

--
-- Indexes for table `luokat`
--
ALTER TABLE `luokat`
  ADD PRIMARY KEY (`LuokkaID`);

--
-- Indexes for table `varattavatajat`
--
ALTER TABLE `varattavatajat`
  ADD PRIMARY KEY (`AikaID`),
  ADD KEY `LuokkaID` (`LuokkaID`);

--
-- Indexes for table `varaukset`
--
ALTER TABLE `varaukset`
  ADD PRIMARY KEY (`VarausID`),
  ADD KEY `KayttajaID` (`KayttajaID`),
  ADD KEY `AikaID` (`AikaID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kayttajat`
--
ALTER TABLE `kayttajat`
  MODIFY `KayttajaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `luokat`
--
ALTER TABLE `luokat`
  MODIFY `LuokkaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `varattavatajat`
--
ALTER TABLE `varattavatajat`
  MODIFY `AikaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `varaukset`
--
ALTER TABLE `varaukset`
  MODIFY `VarausID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `varattavatajat`
--
ALTER TABLE `varattavatajat`
  ADD CONSTRAINT `varattavatajat_ibfk_1` FOREIGN KEY (`LuokkaID`) REFERENCES `luokat` (`LuokkaID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Rajoitteet taululle `varaukset`
--
ALTER TABLE `varaukset`
  ADD CONSTRAINT `varaukset_ibfk_1` FOREIGN KEY (`KayttajaID`) REFERENCES `kayttajat` (`KayttajaID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `varaukset_ibfk_2` FOREIGN KEY (`AikaID`) REFERENCES `varattavatajat` (`AikaID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
