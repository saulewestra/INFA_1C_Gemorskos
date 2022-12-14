CREATE DATABASE IF NOT EXISTS `Gemorskos`
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gemorskos`

--Tabel voor medewerkers
CREATE TABLE `Medewerker`(
`medewerker_id` INT AUTO_INCREMENT NOT NULL;
`medewerker_werk_functie` INT NOT NULL;
`voornaam` VARCHAR(25) NOT NULL;
`achternaam` VARCHAR(25) NOT NULL;
`email` VARCHAR(55) UNIQUE NOT NULL;
`phone_number` VARCHAR(10) UNIQUE NOT NULL;
--`gebruikersnaam` VARCHAR(55) UNIQUE NOT NULL
`wachtwoord` VARCHAR(60) NOT NULL;
PRIMARY KEY(`medewerker_id`),
FOREIGN KEY(`medewerker_werk_functie`) REFERENCES `Werk_Functie`(`werk_functie_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);

--Tabel voor de soorten werk functies
CREATE TABLE `Werk_Functie`(
`werk_functie_id` INT AUTO_INCREMENT NOT NULL;
`functie_naam` VARCHAR(14)
PRIMARY KEY(`werk_funtie_id`)
);

--Tabel voor de evenementen die te zien zijn
CREATE TABLE `Evenement`(
`evenement_id` INT AUTO_INCREMENT NOT NULL;
`evenement_naam` VARCHAR(25) NOT NULL;
`beschrijving` TEXT NOT NULL;
`dag` DATE
`tijd` TIME
`straatnaam` VARCHAR(26) NOT NULL;
`stad` VARCHAR(50) NOT NULL;
`post_code` VARCHAR(6) NOT NULL;
PRIMARY KEY(`evenement_id`)
);

--Tabel voor informatie van de geclaimde events
CREATE TABLE `Evenement_Detail`(
`evenement_detail_id` INT AUTO_INCREMENT NOT NULL;
`medewerker_id` INT NOT NULL;
`evenement_id`INT NOT NULL;
PRIMARY KEY(`evenement_detail_id`),
FOREIGN KEY(`medewerker_id`) REFERENCES `Medewerker`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`evenement_id`) REFERENCES `Evenement`(`evenement_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);