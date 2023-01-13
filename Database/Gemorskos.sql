-- phpMyAdmin SQL Dump
-- version 5.3.0
CREATE DATABASE IF NOT EXISTS `Gemorskos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gemorskos`;

--
-- Tabelstructuur voor tabel `Werk_Functie`
-- Met indexen voor de tabel
--
DROP TABLE IF EXISTS `Werk_Functie`;
CREATE TABLE `Werk_Functie` (
  `werk_functie_id` int(6) NOT NULL,
  `functie_naam` varchar(25) NOT NULL,
  PRIMARY KEY (`werk_functie_id`)
);
--
-- Gegevens worden geëxporteerd voor tabel `Werk_Functie`
--
INSERT INTO `Werk_Functie`(`werk_functie_id`, `functie_naam`) VALUES 
(1, "Hoofdredacteur"), 
(2, "Redacteur"), 
(3, "Journalist"), 
(4, "Fotograaf"), 
(5, "Content Creator"), 
(6, "Freelancer");
--
-- Tabelstructuur voor tabel `Evenement`
-- Met indexen voor de tabel
--
DROP TABLE IF EXISTS `Evenement`;
CREATE TABLE `Evenement` (
  `evenement_id` int AUTO_INCREMENT NOT NULL,
  `evenement_naam` varchar(40) NOT NULL,
  `beschrijving` text NOT NULL,
  `dag` date DEFAULT NULL,
  `tijd` time DEFAULT NULL,
  `straatnaam` varchar(26) NOT NULL,
  `stad` varchar(40) NOT NULL,
  `postcode` varchar(7) NOT NULL,
  PRIMARY KEY (`evenement_id`)
);
--
-- Gegevens worden geëxporteerd voor tabel `Evenement`
--
INSERT INTO `Evenement`(`evenement_id`, `evenement_naam`, `beschrijving`, `dag`, `tijd`, `straatnaam`, `stad`, `postcode`)VALUES 
(NULL, "Schaatsbaan Emmen", "Schaatsbaan is in twee dagen gebouwd en er kan nu volop geschaatst worden. Voor jong en oud is genoeg plek om een warme chocomel te drinken.", "2022-12-22", "11:30:00", "Raadhuisplein", "Emmen", "7811 AP");
--
-- Tabelstructuur voor tabel `Medewerkers`
-- Met indexen voor de tabel
--
DROP TABLE IF EXISTS `Medewerkers`;
CREATE TABLE `Medewerkers` (
  `medewerker_id` int AUTO_INCREMENT NOT NULL,
  `werk_functie_id` int(11) NOT NULL,
  `voornaam` varchar(25) NOT NULL,
  `achternaam` varchar(25) NOT NULL,
  `email` varchar(55) NOT NULL,
  `telefoonnummer` varchar(10) NOT NULL,
  `wachtwoord` varchar(60) NOT NULL,
  PRIMARY KEY (`medewerker_id`),
  FOREIGN KEY (`werk_functie_id`) REFERENCES `Werk_Functie` (`werk_functie_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
--
-- Gegevens worden geëxporteerd voor tabel `Medewerkers`
--
INSERT INTO `Medewerkers`(`medewerker_id`, `werk_functie_id`, `voornaam`, `achternaam`, `email`, `telefoonnummer`, `wachtwoord`) VALUES 
(NULL, 1, "Bart", "Oerlemans", "B.Oerlemans@gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 2, "Erik", "Jakops", "E.Jakops@Gemorskos.nl", "31-1234567", "Gemorskos01"), 
(NULL, 2, "Jonny", "West", "J.West@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 2, "Jasper", "Roberts", "J.Roberts@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 3, "Hendrik", "Oever", "H.Oever@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 3, "Piet", "Zwart", "P.Zwart@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 3, "Sjaak", "Afhaak", "S.Afhaak@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 3, "Martijn", "Oostenbeek", "M.Oostenbeek@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 3, "Sander", "Peters", "S.P@Gemorskos", "31-1234567", "Gemorskos01"),
(NULL, 4, "Bas", "Kuiper", "B.Kuiper@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 4, "James", "Frits", "J.Frits@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 4, "Kas", "Oranje", "K.Oranje@Gemorskos.nl", "31-1234567", "Gemorskos01"),
(NULL, 6, "Thomas", "Oole", "T.Oole@Gemorskos.nl", "31-1234567", "Gemorskos01");
--
-- Tabelstructuur voor tabel `Bestand`
-- Met indexen voor de tabel
--
DROP TABLE IF EXISTS `Bestand`;
CREATE TABLE `Bestand` (
  `bestand_id` int AUTO_INCREMENT NOT NULL,
  `medewerker_id` int(11) NOT NULL,
  `evenement_id` int(11) NOT NULL,
  `bestandsnaam` varchar(40) DEFAULT NULL,
  `bestand_grootte_byte` int(12) NOT NULL,
  `bestand_type` varchar(10) NOT NULL,
  `upload_datum` date DEFAULT NULL,
  `beschrijving` text NOT NULL,
  PRIMARY KEY (`bestand_id`),
  FOREIGN KEY (`medewerker_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`evenement_id`) REFERENCES `Evenement` (`evenement_id`) ON DELETE CASCADE ON UPDATE CASCADE
);
--
-- Tabelstructuur voor tabel `Evenement_Detail`
-- Met indexen voor de tabel
--
DROP TABLE IF EXISTS `Evenement_Detail`;
CREATE TABLE `Evenement_Detail` (
  `redacteur_id` int(11) DEFAULT NULL,
  `journalist_id` int(11) DEFAULT NULL,
  `fotograaf_id` int(11) DEFAULT NULL,
  `evenement_id` int(11) NOT NULL,
  FOREIGN KEY (`redacteur_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`journalist_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`fotograaf_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`evenement_id`) REFERENCES `Evenement` (`evenement_id`) ON DELETE CASCADE ON UPDATE CASCADE
);