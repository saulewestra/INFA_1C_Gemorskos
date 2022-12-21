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
                $db = new PDO("mysql:host=mysql;dbname=gemorskos;charset=utf8", "root", "qwerty");
                $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement(evenement_id INT AUTO_INCREMENT NOT NULL, evenement_naam VARCHAR(40) NOT NULL, beschrijving TEXT NOT NULL, dag DATE, tijd TIME, straatnaam VARCHAR(26) NOT NULL, stad VARCHAR(40) NOT NULL, postcode VARCHAR(6) NOT NULL, PRIMARY KEY(evenement_id))");
                $cursor->execute();
                $cursor->closeCursor();
                $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement_Detail(medewerker1_id INT NOT NULL, medewerker2_id INT NOT NULL, evenement_id INT NOT NULL, FOREIGN KEY(medewerker1_id) REFERENCES Medewerker(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(medewerker2_id) REFERENCES Medewerker(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(evenement_id) REFERENCES Evenement(evenement_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
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

            function showEvent(PDO $db, array $event): void {
                echo '';
            }

            function getEvent(PDO $db, int $id): array | false {
                try {
                    $cursor = $db->prepare("SELECT Evenement.evenement_naam, Evenement.beschrijving, Evenement.dag, Evenement.tijd, Evenement.straatnaam, Evenement.stad, Evenement.postcode, Evenement_Detail.medewerker1_id, Evenement_Detail.medewerker2_id FROM Evenement WHERE Evenement.evenement_id = :id JOIN Evenement_Detail ON Evenement.evenement_id = Evenement_Detail.evenement_id");
                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                    $cursor->execute();
                    $result = $cursor->fetchAll(PDO::FETCH_ASSOC);
                    return (sizeof($result) > 0) ? $result[0] : false;
                } catch (Exception $exc) {
                    return false;
                }
            }

            if (isset($db)) {
                if ($_SERVER["REQUEST_METHOD"] != "GET" || !($id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)) || !($event = getEvent($db, $id))) {
                    showError("Dit event bestaat niet.");
                } else {
                    showEvent($db, $event);
                }
            }
            ?>
            <div id="background"></div>
        </div>
    </body>
</html>