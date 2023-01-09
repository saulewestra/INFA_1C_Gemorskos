CREATE DATABASE IF NOT EXISTS `Gemorskos`
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gemorskos`

CREATE TABLE `Werk_Functie`(
`werk_functie_id` INT AUTO_INCREMENT NOT NULL,
`functie_naam` VARCHAR(25) NOT NULL,
PRIMARY KEY(`werk_functie_id`)
);

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