<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <title>Event</title>
    </head>
    <body>
        <div id="container">
            <header id="header">
                <div id="logo">
                    <h1>Gemorskos</h1>
                    <h3>Wij maken kranten</h3>
                </div>
                <img id="profile" src="../img/profilepic.png" alt="Profile">
            </header>
            <?php
            try {
                $db = new PDO("mysql:host=mysql;dbname=Gemorskos;charset=utf8", "root", "qwerty");
                $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement(evenement_id INT AUTO_INCREMENT NOT NULL, evenement_naam VARCHAR(40) NOT NULL, beschrijving TEXT NOT NULL, datum DATE, straatnaam VARCHAR(26) NOT NULL, stad VARCHAR(40) NOT NULL, postcode VARCHAR(6) NOT NULL, PRIMARY KEY(evenement_id))");
                $cursor->execute();
                $cursor->closeCursor();
                $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Werk_Functie(werk_functie_id INT AUTO_INCREMENT NOT NULL, functie_naam VARCHAR(14) NOT NULL, PRIMARY KEY(werk_functie_id))");
                $cursor->execute();
                $cursor->closeCursor();
                $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Medewerkers(medewerker_id INT AUTO_INCREMENT NOT NULL, werk_functie_id INT NOT NULL, voornaam VARCHAR(25) NOT NULL, achternaam VARCHAR(25) NOT NULL, email VARCHAR(55) UNIQUE NOT NULL, telefoonnummer VARCHAR(10) UNIQUE NOT NULL, wachtwoord VARCHAR(60) NOT NULL, PRIMARY KEY(medewerker_id), FOREIGN KEY(werk_functie_id) REFERENCES Werk_Functie(werk_functie_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
                $cursor->execute();
                $cursor->closeCursor();
                $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement_Detail(journalist_id INT NOT NULL, fotograaf_id INT NOT NULL, evenement_id INT NOT NULL, FOREIGN KEY(journalist_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(fotograaf_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(evenement_id) REFERENCES Evenement(evenement_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
                $cursor->execute();
                $cursor->closeCursor();
            } catch (Exception $exc) {
                showError("De database is op dit moment niet bereikbaar. Probeer het later nog eens.");
            }

            function showError(string $error): void {
                echo '<main id="content">
                    <h1>'.$error.'</h1>
                    <h3>Klik <a href=".">hier</a> om terug te gaan</h3>
                </main>';
            }

            function showEvent(array $event): void {
                echo '<main id="event">
                    <h1>'.$event["evenement_naam"].'</h1>
                    <p>'.$event["beschrijving"].'</p>
                    <h4>Datum: '.$event["datum"].'</h4>
                    <h4>Straat: '.$event["straat"].'</h4>
                    <h4>Stad: '.$event["stad"].'</h4>
                    <h4>Postcode: '.$event["postcode"].'</h4>
                    <div id="buttons">
                        <form method="POST">
                            <input type="submit" value="Claim">
                        </form>
                    </div>
                </main>';
            }

            function getEvent(PDO $db, int $id): array | false {
                return [
                    "evenement_naam" => "test evenement",
                    "beschrijving" => "test",
                    "datum" => "woensdag 12 uur",
                    "straat" => "hardenbergerweg",
                    "stad" => "marienberg",
                    "postcode" => "7692PA",
                    "redacteur_id" => "ikkuh",
                    "journalist_id" => "raevun",
                    "fotograaf_id" => "kaas"
                ];
                // try {
                //     $cursor = $db->prepare("SELECT Evenement.evenement_naam, Evenement.beschrijving, Evenement.datum, Evenement.straatnaam, Evenement.stad, Evenement.postcode, Evenement_Detail.redacteur_id, Evenement_Detail.journalist_id, Evenement_Detail.fotograaf_id FROM Evenement WHERE Evenement.evenement_id = :id JOIN Evenement_Detail ON Evenement.evenement_id = Evenement_Detail.evenement_id");
                //     $cursor->bindParam("id", $id, PDO::PARAM_INT);
                //     $cursor->execute();
                //     $result = $cursor->fetch(PDO::FETCH_ASSOC);
                //     $cursor->closeCursor();
                //     if (!$result) {
                //         return false;
                //     } else {
                //         return $result;
                //     }
                // } catch (Exception $exc) {
                //     return false;
                // }
            }

            if (isset($db)) {
                if (!($id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)) || !($event = getEvent($db, $id))) {
                    showError("Dit event bestaat niet.");
                } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    showEvent($event);
                } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                }
            }
            ?>
            <div id="background"></div>
        </div>
    </body>
</html>