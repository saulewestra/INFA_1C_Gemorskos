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
            <main id="event">
                <h1>Voorbeeld event</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in neque enim. Quisque volutpat dui euismod, pretium mi at, aliquam ante. Aliquam aliquam vel dolor id pellentesque. Integer erat nisi, ultrices commodo tortor elementum, pellentesque sollicitudin nibh. Nullam scelerisque nulla eget finibus tempus. Ut a semper lorem, ut tincidunt ligula. Mauris commodo, est ut aliquet vulputate, sapien quam vehicula purus, ut interdum leo dolor ut sem. Pellentesque vitae mattis erat. Phasellus cursus a ligula vel posuere. Suspendisse id lorem vel quam blandit molestie. In et turpis nisl. Aliquam auctor porta diam, ut suscipit tellus luctus sollicitudin. Vivamus congue, ante in tristique molestie, urna sapien malesuada augue, ut feugiat leo nisi eu orci. Morbi quis sapien egestas, viverra urna sit amet, vehicula purus. Proin ipsum tellus, vehicula vel ornare eu, cursus ut ex. Vestibulum mollis dictum nisi, sed blandit turpis congue id.</p>
                <h4>Datum: 21/12/2022 12:53</h4>
                <h4>Straat: Hardenbergerweg</h4>
                <h4>Stad: Mariënberg</h4>
                <h4>Postcode: 7692PA</h4>
                <div id="buttons">
                    <form method="POST">
                        <input type="submit" value="Claim">
                    </form>
                </div>
            </main>
            <?php
            // try {
            //     $db = new PDO("mysql:host=mysql;dbname=Gemorskos;charset=utf8", "root", "qwerty");
            //     $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement(evenement_id INT AUTO_INCREMENT NOT NULL, evenement_naam VARCHAR(40) NOT NULL, beschrijving TEXT NOT NULL, dag DATE, tijd TIME, straatnaam VARCHAR(26) NOT NULL, stad VARCHAR(40) NOT NULL, postcode VARCHAR(6) NOT NULL, PRIMARY KEY(evenement_id))");
            //     $cursor->execute();
            //     $cursor->closeCursor();
            //     $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Werk_Functie(werk_functie_id INT AUTO_INCREMENT NOT NULL, functie_naam VARCHAR(14) NOT NULL, PRIMARY KEY(werk_functie_id))");
            //     $cursor->execute();
            //     $cursor->closeCursor();
            //     $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Medewerkers(medewerker_id INT AUTO_INCREMENT NOT NULL, werk_functie_id INT NOT NULL, voornaam VARCHAR(25) NOT NULL, achternaam VARCHAR(25) NOT NULL, email VARCHAR(55) UNIQUE NOT NULL, telefoonnummer VARCHAR(10) UNIQUE NOT NULL, wachtwoord VARCHAR(60) NOT NULL, PRIMARY KEY(medewerker_id), FOREIGN KEY(werk_functie_id) REFERENCES Werk_Functie(werk_functie_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
            //     $cursor->execute();
            //     $cursor->closeCursor();
            //     $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement_Detail(medewerker1_id INT NOT NULL, medewerker2_id INT NOT NULL, evenement_id INT NOT NULL, FOREIGN KEY(medewerker1_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(medewerker2_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(evenement_id) REFERENCES Evenement(evenement_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
            //     $cursor->execute();
            //     $cursor->closeCursor();
            // } catch (Exception $exc) {
            //     showError("De database is op dit moment niet bereikbaar. Probeer het later nog eens.");
            // }

            // function showError(string $error): void {
            //     echo '<main id="content">
            //         <h1>'.$error.'</h1>
            //         <h3>Klik <a href=".">hier</a> om terug te gaan</h3>
            //     </main>';
            // }

            // function showEvent(array $event): void {
            //     echo '';
            // }

            // function getEvent(PDO $db, int $id): array | false {
            //     return [
            //         "name" => "test evenement",
            //         "description" => "test",
            //         "day" => "woensdag",
            //         "time" => "12 uur",
            //         "street" => "hardenbergerweg",
            //         "city" => "marienberg",
            //         "zip" => "7692PA",
            //         "journalist" => "raevun",
            //         "photographer" => "kaas"
            //     ];
            //     // try {
            //     //     $cursor = $db->prepare("SELECT Evenement.evenement_naam, Evenement.beschrijving, Evenement.dag, Evenement.tijd, Evenement.straatnaam, Evenement.stad, Evenement.postcode, Evenement_Detail.medewerker1_id, Evenement_Detail.medewerker2_id FROM Evenement WHERE Evenement.evenement_id = :id JOIN Evenement_Detail ON Evenement.evenement_id = Evenement_Detail.evenement_id");
            //     //     $cursor->bindParam("id", $id, PDO::PARAM_INT);
            //     //     $cursor->execute();
            //     //     $result = $cursor->fetch(PDO::FETCH_ASSOC);
            //     //     $cursor->closeCursor();
            //     //     if (!$result) {
            //     //         return false;
            //     //     } else {
            //     //         $cursor = $db->prepare("SELECT voornaam, achternaam FROM Medewerkers WHERE medewerker_id = :medewerker");
            //     //         $cursor->bindParam("medewerker", $result["mederwerker1_id"]);
            //     //         $cursor->execute();
            //     //         $medewerker = $cursor->fetch(PDO::FETCH_NUM);
            //     //         if (!$medewerker) {
            //     //             return false;
            //     //         }
            //     //         $medewerker1 = $medewerker[0].$medewerker[1];
            //     //         $cursor = $db->prepare("SELECT voornaam, achternaam FROM Medewerkers WHERE medewerker_id = :medewerker");
            //     //         $cursor->bindParam("medewerker", $result["mederwerker2_id"]);
            //     //         $cursor->execute();
            //     //         $medewerker = $cursor->fetch(PDO::FETCH_NUM);
            //     //         if (!$medewerker) {
            //     //             return false;
            //     //         }
            //     //         $medewerker2 = $medewerker[0].$medewerker[1];
            //     //         return ["name" => $result["evenement_naam"], "description" => $result["beschrijving"], "day" => $result["day"], "time" => $result["time"], "street" => $result["straatnaam"], "city" => $result["stad"], "zip" => $result["postcode"], "journalist" => $medewerker1, "photographer" => $medewerker2];
            //     //     }
            //     // } catch (Exception $exc) {
            //     //     return false;
            //     // }
            // }

            // if (isset($db)) {
            //     if ($_SERVER["REQUEST_METHOD"] != "GET" || !($id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)) || !($event = getEvent($db, $id))) {
            //         showError("Dit event bestaat niet.");
            //     } else {
            //         showEvent($event);
            //     }
            // }
            ?>
            <div id="background"></div>
        </div>
    </body>
</html>