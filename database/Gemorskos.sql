CREATE DATABASE IF NOT EXISTS `Gemorskos`
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gemorskos`

--Table 1, Werk Functie
CREATE TABLE `Werk_Functie`(
`werk_functie_id` INT AUTO_INCREMENT NOT NULL,
`functie_naam` VARCHAR(25) NOT NULL,
PRIMARY KEY(`werk_functie_id`)
);

-- Table 1, INSERT INTO 
INSERT INTO `Werk_Functie`(`werk_functie_id`, `functie_naam`)
VALUES (1, "Hoofdredacteur"), (2, "Redacteur"), (3, "Journalist"), (4, "Fotograaf"), (5, "Content Creator"), (6, "Freelancer");

-- Table 2, Evenement
CREATE TABLE `Evenement`(
`evenement_id` INT AUTO_INCREMENT NOT NULL,
`evenement_naam` VARCHAR(40) NOT NULL,
`beschrijving` TEXT NOT NULL,
`datum` DATE,
`straatnaam` VARCHAR(26) NOT NULL,
`stad` VARCHAR(40) NOT NULL,
`postcode` VARCHAR(6) NOT NULL,
PRIMARY KEY(`evenement_id`)
);

-- Table 3, Medewerkers met FOREIGN KEY
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

-- Table 4, Evenement_Detail met FOREIGN KEY
CREATE TABLE `Evenement_Detail`(
`redacteur_id` INT,
`journalist_id` INT,
`fotograaf_id` INT,
`evenement_id`INT NOT NULL,
FOREIGN KEY (`redacteur_id`) REFERENCES `Medewerkers`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`journalist_id`) REFERENCES `Medewerkers`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`fotograaf_id`) REFERENCES `Medewerkers`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`evenement_id`) REFERENCES `Evenement`(`evenement_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- Table 5, Bestand met FOREIGN KEY
CREATE TABLE `Bestand`(
`bestand_id` INT AUTO_INCREMENT NOT NULL,
`medewerker_id` INT NOT NULL,
`evenement_id` INT NOT NULL,
`bestandsnaam` VARCHAR(40),
`bestand_grootte_MB` INT NOT NULL,
`bestand_type` VARCHAR(10) NOT NULL,
`upload_datum` DATE,
`beschrijving` TEXT NOT NULL,
PRIMARY KEY(`bestand_id`),
FOREIGN KEY(`medewerker_id`) REFERENCES `Medewerkers`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`evenement_id`) REFERENCES `Evenement`(`evenement_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);