--Project Database Application Managment, Gemorskos

CREATE DATABASE IF NOT EXISTS `Gemorskos`
DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Gemorskos`

-- 1. Tabel voor medewerkers
CREATE TABLE `Medewerkers`(
`medewerker_id` INT AUTO_INCREMENT NOT NULL;
`werk_functie_id` INT NOT NULL;
`voornaam` VARCHAR(25) NOT NULL;
`achternaam` VARCHAR(25) NOT NULL;
`email` VARCHAR(55) UNIQUE NOT NULL;
`telefoonnummer` VARCHAR(10) UNIQUE NOT NULL;
PRIMARY KEY(`medewerker_id`),
FOREIGN KEY(`werk_functie_id`) REFERENCES `Werk_Functie`(`werk_functie_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
);

-- 2. Tabel voor de soorten werk functies
CREATE TABLE `Werk_Functie`(
`werk_functie_id` INT AUTO_INCREMENT NOT NULL;
`functie_naam` VARCHAR(14) NOT NULL; CREATE DATABASE IF NOT EXISTS `Gemorskos`
PRIMARY KEY(`werk_functie_id`)
);

-- 3. Tabel voor het inloggen van de medewerker
CREATE TABLE `Inlog`(
`inlog_id` INT AUTO_INCREMENT NOT NULL;
`username` VARCHAR(55) UNIQUE NOT NULL;
`wachtwoord` VARCHAR(60) NOT NULL;
`medewerker_id` INT NOT NULL;
PRIMARY KEY(`inlog_id`),
FOREIGN KEY(`medewerker_id`) REFERENCES `Medewerkers`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- 4. Tabel voor de evenementen die te zien zijn
CREATE TABLE `Evenement`(
`evenement_id` INT AUTO_INCREMENT NOT NULL;
`evenement_naam` VARCHAR(40) NOT NULL;
`beschrijving` TEXT NOT NULL;
`dag` DATE
`tijd` TIME
`straatnaam` VARCHAR(26) NOT NULL;
`stad` VARCHAR(40) NOT NULL;
`postcode` VARCHAR(6) NOT NULL;
PRIMARY KEY(`evenement_id`)
);

-- 5. Tabel voor informatie van de geclaimde events
CREATE TABLE `Evenement_Detail`(
`medewerker_id` INT NOT NULL;
`evenement_id`INT NOT NULL;
FOREIGN KEY(`medewerker_id`) REFERENCES `Medewerker`(`medewerker_id`) ON UPDATE CASCADE ON DELETE NO ACTION,
FOREIGN KEY(`evenement_id`) REFERENCES `Evenement`(`evenement_id`) ON UPDATE CASCADE ON DELETE NO ACTION
);

-- 6. Tabel voor Bestanden
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


-- FOREIGN KEY toevoegen

-- ALTER TABLE `Medewerkers`
-- ADD FOREIGN KEY(`medewerker_werk_functie`) REFERENCES `Werk_Functie`(`werk_functie_id`)
-- ON UPDATE CASCADE ON DELETE NO ACTION;