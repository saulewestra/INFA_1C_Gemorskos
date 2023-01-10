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
INSERT INTO `Werk_Functie`(`werk_functie_id`, `functie_naam`)
VALUES (1, "Hoofdredacteur"), (2, "Redacteur"), (3, "Journalist"), (4, "Fotograaf"), (5, "Content Creator"), (6, "Freelancer");
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
  `postcode` varchar(6) NOT NULL,
  PRIMARY KEY (`evenement_id`)
);
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
  FOREIGN KEY (`werk_functie_id`) REFERENCES `Werk_Functie` (`werk_functie_id`) ON DELETE NO ACTION ON UPDATE CASCADE
);
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
  `bestand_grootte_MB` int(11) NOT NULL,
  `bestand_type` varchar(10) NOT NULL,
  `upload_datum` date DEFAULT NULL,
  `beschrijving` text NOT NULL,
  PRIMARY KEY (`bestand_id`),
  FOREIGN KEY (`medewerker_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (`evenement_id`) REFERENCES `Evenement` (`evenement_id`) ON DELETE NO ACTION ON UPDATE CASCADE
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
  FOREIGN KEY (`redacteur_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (`journalist_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (`fotograaf_id`) REFERENCES `Medewerkers` (`medewerker_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (`evenement_id`) REFERENCES `Evenement` (`evenement_id`) ON DELETE NO ACTION ON UPDATE CASCADE
);