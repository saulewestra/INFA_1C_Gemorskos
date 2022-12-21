
CREATE DATABASE IF NOT EXISTS `Gemorskos`
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gemorskos`

CREATE TABLE `Medewerkers`(
`medewerker_id` INT AUTO_INCREMENT NOT NULL,
`werk_functie_id` INT NOT NULL,
`voornaam` VARCHAR(25) NOT NULL,
`achternaam` VARCHAR(25) NOT NULL,
`email` VARCHAR(55) UNIQUE NOT NULL,
`telefoonnummer` VARCHAR(10) UNIQUE NOT NULL,
`wachtwoord` VARCHAR(60) NOT NULL,
PRIMARY KEY(`medewerker_id`),
FOREIGN KEY(`werk_functie_id`) REFERENCES `Werk_Functie`(`werk_functie_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE `Werk_Functie`(
`werk_functie_id` INT AUTO_INCREMENT NOT NULL,
`functie_naam` VARCHAR(14) NOT NULL,
PRIMARY KEY(`werk_functie_id`)
);

CREATE TABLE `Evenement`(
`evenement_id` INT AUTO_INCREMENT NOT NULL,
`evenement_naam` VARCHAR(40) NOT NULL,
`beschrijving` TEXT NOT NULL,
`dag` DATE,
`tijd` TIME,
`straatnaam` VARCHAR(26) NOT NULL,
`stad` VARCHAR(40) NOT NULL,
`postcode` VARCHAR(6) NOT NULL,
PRIMARY KEY(`evenement_id`)
);

CREATE TABLE `Evenement_Detail`(
`medewerker1_id` INT NOT NULL,
`medewerker2_id` INT NOT NULL,
`evenement_id`INT NOT NULL,
FOREIGN KEY(`medewerker1_id`) REFERENCES `Medewerker`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`medewerker2_id`) REFERENCES `Medewerker`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`evenement_id`) REFERENCES `Evenement`(`evenement_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);

CREATE TABLE `Bestand`(
`bestand_id` INT AUTO_INCREMENT NOT NULL;
`medewerker_id` INT NOT NULL;
`evenement_id` INT NOT NULL;
`bestandsnaam` VARCHAR(40);
`bestand_grootte_MB` INT NOT NULL;
`bestand_type` VARCHAR(10) NOT NULL;
`upload_datum` DATE;
`beschrijving` TEXT NOT NULL;
PRIMARY KEY(`bestand_id`),
FOREIGN KEY(`medewerker_id`) REFERENCES `Medewerkers`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`evenement_id`) REFERENCES `Evenement`(`evenement_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);