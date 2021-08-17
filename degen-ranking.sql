-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 17. Aug 2021 um 15:56
-- Server-Version: 5.7.24
-- PHP-Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `degen-ranking`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `votes` int(255) NOT NULL DEFAULT '0',
  `voted_at` timestamp NOT NULL DEFAULT '2010-10-10 06:10:10',
  `vote_desc` varchar(255) DEFAULT NULL,
  `top_voted` int(255) DEFAULT NULL,
  `total_votes` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `votes`, `voted_at`, `vote_desc`, `top_voted`, `total_votes`) VALUES
(1, 'Ango', '$2y$10$WGSTCqyJCfNDEbsn25L6bODdoul7z6wJk8c6KD0L1XWB59wjVppCW', 8, '2021-08-17 15:12:41', '', 1, 4),
(2, 'test_user', '$2y$10$yrdPL.TpKqoyDniScuIygudQv2Gx1Do3VfXMuWNLKF0gSXPeNPazu', 2, '0000-00-00 00:00:00', '', 0, 0),
(3, 'test2', '$2y$10$suRO0naYoP8I3JDc8XfBnOcsD7thloz8OWETmIkdoBsPeY8eo9Pcu', 3, '2021-08-17 15:13:50', 'einfach nurr so', 1, 1),
(4, 'test3', '$2y$10$VRNadjdgrZEXIk78.uim5eRDRYGw2KcEKH.GmKO50ZABLT.2WML0e', 1, '2021-08-17 15:02:11', '', 1, 2);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
