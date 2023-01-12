<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../style/style.css">
        <title>Event</title>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <div id="logo">
                    <h1>Gemorskos</h1>
                    <h3>Wij maken kranten</h3>
                </div>
                <img id="profile" src="../img/profilepic.png" alt="Profile">
            </header>
            <?php
            if (!isset($_SESSION["id"])) {
                echo '<main id="content">
                    <h1>Je bent niet ingelogd</h1>
                    <h3>Klik <a href="login.php">hier</a> om naar de inlogpagina te gaan</h3>
                </main>';
            } else {
                $medewerker_id = $_SESSION["id"];

                function showMessage(string $message, int $id = null): void {
                    $href = (isset($id)) ? "./?id=".$id : ".";
                    echo '<main id="content">
                        <h1>'.$message.'</h1>
                        <h3>Klik <a href="'.$href.'">hier</a> om terug te gaan</h3>
                    </main>';
                }

                function showEvent(array $event, array $claims): void {
                    echo '<main id="event">
                        <h1>'.$event["evenement_naam"].'</h1>
                        <p>'.$event["beschrijving"].'</p>
                        <h4>Datum: '.$event["datum"].'</h4>
                        <h4>Straat: '.$event["straat"].'</h4>
                        <h4>Stad: '.$event["stad"].'</h4>
                        <h4>Postcode: '.$event["postcode"].'</h4>
                        <div id="buttons">
                        <form method="POST">
                    ';
                    if (!$claims["redactor"] && !$claims["journalist"] && !$claims["photographer"]) {
                        echo '<input type="submit" value="Claim">';
                    } else {
                        if ($claims["redactor"]) {
                            echo '<input type="submit" name="action" value="Bekijk Submissies">';
                        }
                        if ($claims["journalist"]) {
                            echo '<input type="submit" name="action" value="Voeg Tekst Toe">';
                        }
                        if ($claims["photographer"]) {
                            echo '<input type="submit" name="action" value="Upload Foto\'s">';
                        }
                    }
                    echo '</form>
                        </div>
                    </main>';
                }

                function checkClaims(PDO $db, int $id): array {
                    global $medewerker_id;
                    try {
                        $cursor = $db->prepare("SELECT Evenement_Detail.redacteur_id, Evenement_Detail.journalist_id, Evenement_Detail.fotograaf_id FROM Evenement_Detail WHERE Evenement_Detail.evenement_id = :id JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id");
                        $cursor->bindParam("id", $id, PDO::PARAM_INT);
                        $cursor->execute();
                        $result = $cursor->fetch(PDO::FETCH_ASSOC);
                        $cursor->closeCursor();
                        if (!$result) {
                            return ["redactor" => false, "journalist" => false, "photographer" => false];
                        } else {
                            return ["redactor" => $result["redacteur_id"] == $medewerker_id, "journalist" => $result["journalist_id"] == $medewerker_id, "photographer" => $result["fotograaf_id"] == $medewerker_id];
                        }
                    } catch (Exception $exc) {
                        return ["redactor" => false, "journalist" => false, "photographer" => false];
                    }
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

                function claimEvent(PDO $db, int $id, bool $redacteur, bool $journalist, bool $fotograaf): void {
                    if (!isset($_SESSION["id"])) {
                        echo '<main id="content">
                            <h1>Je bent niet ingelogd</h1>
                            <h3>Klik <a href="login.php">hier</a> om naar de inlogpagina te gaan</h3>
                        </main>';
                    } else {
                        try {
                            $functions = checkFunctions($db);
                            if ($redacteur && !$functions["redactor"]) {
                                showMessage("U kunt deze rol niet claimen.", $id);
                            } else if ($journalist && !$functions["journalist"]) {
                                showMessage("U kunt deze rol niet claimen.", $id);
                            } else if ($fotograaf && !$functions["photographer"]) {
                                showMessage("U kunt deze rol niet claimen.", $id);
                            } else {
                                if ($redacteur) {
                                    $cursor = $db->prepare("SELECT Evenement_Detail.redacteur_id FROM Evenement_Detail WHERE Evenement_Detail.evenement_id = :id JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id");
                                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                                    $cursor->execute();
                                    $result = $cursor->fetch();
                                    $cursor->closeCursor();
                                    if ($result) {
                                        showMessage("Dit event heeft al een redacteur.", $id);
                                    } else {
                                        $cursor = $db->prepare("UPDATE Evenement_Detail SET redacteur_id = :id WHERE evenement_id = :event_id");
                                        $cursor->bindParam("id", $medewerker_id, PDO::PARAM_INT);
                                        $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                        $cursor->execute();
                                        $cursor->closeCursor();
                                    }
                                }
                                if ($journalist) {
                                    $cursor = $db->prepare("SELECT Evenement_Detail.journalist_id FROM Evenement_Detail WHERE Evenement_Detail.evenement_id = :id JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id");
                                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                                    $cursor->execute();
                                    $result = $cursor->fetch();
                                    $cursor->closeCursor();
                                    if ($result) {
                                        showMessage("Dit event heeft al een journalist.", $id);
                                    } else {
                                        $cursor = $db->prepare("UPDATE Evenement_Detail SET journalist_id = :id WHERE evenement_id = :event_id");
                                        $cursor->bindParam("id", $medewerker_id, PDO::PARAM_INT);
                                        $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                        $cursor->execute();
                                        $cursor->closeCursor();
                                    }
                                }
                                if ($fotograaf) {
                                    $cursor = $db->prepare("SELECT Evenement_Detail.fotograaf_id FROM Evenement_Detail WHERE Evenement_Detail.evenement_id = :id JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id");
                                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                                    $cursor->execute();
                                    $result = $cursor->fetch();
                                    $cursor->closeCursor();
                                    if ($result) {
                                        showMessage("Dit event heeft al een fotograaf.", $id);
                                    } else {
                                        $cursor = $db->prepare("UPDATE Evenement_Detail SET fotograaf_id = :id WHERE evenement_id = :event_id");
                                        $cursor->bindParam("id", $medewerker_id, PDO::PARAM_INT);
                                        $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                        $cursor->execute();
                                        $cursor->closeCursor();
                                    }
                                }
                                showMessage("Rollen succesvol geclaimed.");
                            }
                        } catch (Exception $exc) {
                            showMessage("Er is iets fout gegaan. Probeer het later opnieuw.", $id);
                        }
                    }
                }

                function checkFunctions(PDO $db): array {
                    $cursor = $db->prepare("SELECT werk_functie_id FROM Medewerkers WHERE medewerker_id = :id");
                    $cursor->bindParam("id", $medewerker_id, PDO::PARAM_INT);
                    $cursor->execute();
                    $werk_functie = $cursor->fetch(PDO::FETCH_NUM);
                    $cursor->closeCursor();
                    if (!$werk_functie) {
                        return ["redactor" => false, "journalist" => false, "photographer" => false];
                    } else {
                        $functions = [];
                        $functions["redactor"] = $werk_functie[0] == 2;
                        $functions["journalist"] = $werk_functie[0] == 3 || $werk_functie[0] == 6;
                        $functions["photographer"] = $werk_functie[0] == 4 || $werk_functie[0] == 6;
                        return $functions;
                    }
                }

                function showClaim(array $functions): void {
                    $redactorDisabled = ($functions["redactor"]) ? "" : " disabled";
                    $journalistDisabled = ($functions["journalist"]) ? "" : " disabled";
                    $photographerDisabled = ($functions["photographer"]) ? "" : " disabled";
                    echo '<main id="claim">
                        <form method="POST">
                            <div>
                                <input id="redacteur" type="checkbox" name="claims[]" value="redacteur"'.$redactorDisabled.'>
                                <label for="redacteur">Redacteur</label>
                            </div>
                            <div>
                                <input id="journalist" type="checkbox" name="claims[]" value="journalist"'.$journalistDisabled.'>
                                <label for="journalist">Journalist</label>
                            </div>
                            <div>
                                <input id="fotograaf" type="checkbox" name="claims[]" value="fotograaf"'.$photographerDisabled.'>
                                <label for="fotograaf">Fotograaf</label>
                            </div>
                            <input id="submitclaim" type="submit" value="Verzenden">
                        </form>
                    </main>';
                }

                try {
                    $db = new PDO("mysql:host=mysql;dbname=Gemorskos;charset=utf8", "root", "qwerty");
                    $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement(evenement_id INT AUTO_INCREMENT NOT NULL, evenement_naam VARCHAR(40) NOT NULL, beschrijving TEXT NOT NULL, dag DATE DEFAULT NULL, tijd TIME DEFAULT NULL, straatnaam VARCHAR(26) NOT NULL, stad VARCHAR(40) NOT NULL, postcode VARCHAR(6) NOT NULL, PRIMARY KEY(evenement_id))");
                    $cursor->execute();
                    $cursor->closeCursor();
                    $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Werk_Functie(werk_functie_id INT AUTO_INCREMENT NOT NULL, functie_naam VARCHAR(14) NOT NULL, PRIMARY KEY(werk_functie_id))");
                    $cursor->execute();
                    $cursor->closeCursor();
                    $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Medewerkers(medewerker_id INT AUTO_INCREMENT NOT NULL, werk_functie_id INT NOT NULL, voornaam VARCHAR(25) NOT NULL, achternaam VARCHAR(25) NOT NULL, email VARCHAR(55) UNIQUE NOT NULL, telefoonnummer VARCHAR(10) UNIQUE NOT NULL, wachtwoord VARCHAR(60) NOT NULL, PRIMARY KEY(medewerker_id), FOREIGN KEY(werk_functie_id) REFERENCES Werk_Functie(werk_functie_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
                    $cursor->execute();
                    $cursor->closeCursor();
                    $cursor = $db->prepare("CREATE TABLE IF NOT EXISTS Evenement_Detail(redacteur_id INT DEFAULT NULL, journalist_id INT DEFAULT NULL, fotograaf_id INT DEFAULT NULL, evenement_id INT NOT NULL, FOREIGN KEY(redacteur_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(journalist_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(fotograaf_id) REFERENCES Medewerkers(medewerker_id) ON UPDATE CASCADE ON DELETE NO ACTION, FOREIGN KEY(evenement_id) REFERENCES Evenement(evenement_id) ON UPDATE CASCADE ON DELETE NO ACTION)");
                    $cursor->execute();
                    $cursor->closeCursor();
                } catch (Exception $exc) {
                    showMessage("De database is op dit moment niet bereikbaar. Probeer het later nog eens.");
                }
                if (isset($db)) {
                    if (!($id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)) || !$event = getEvent($db, $id)) {
                        showMessage("Dit event bestaat niet.");
                    } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                        showEvent($event, checkClaims($db, $id));
                    } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST["claims"])) {
                            $claims = $_POST["claims"];
                            $redacteur = false;
                            $journalist = false;
                            $fotograaf = false;
                            if (is_string($claims)) {
                                if (!($claim = filter_var($claims, FILTER_SANITIZE_SPECIAL_CHARS))) {
                                    showMessage("Er is iets fout gegaan. Probeer het later opnieuw.", $id);
                                } else {
                                    $redacteur = $claim == "redacteur";
                                    $journalist = $claim == "journalist";
                                    $fotograaf = $claim == "fotograaf";
                                }
                            } else if (is_array($claims)) {
                                foreach($claims as $claim) {
                                    if (!($claim = filter_var($claim, FILTER_SANITIZE_SPECIAL_CHARS))) {
                                        showMessage("Er is iets fout gegaan. Probeer het later opnieuw.", $id);
                                    } else {
                                        $redacteur = $claim == "redacteur";
                                        $journalist = $claim == "journalist";
                                        $fotograaf = $claim == "fotograaf";
                                    }
                                }
                            }
                            if ($redacteur || $journalist || $fotograaf) {
                                claimEvent($db, $id, $redacteur, $journalist, $fotograaf);
                            } else {
                                showMessage("Geen rollen geclaimed.", $id);
                            }
                        } else if (!($action = filter_input(INPUT_POST, "action", FILTER_SANITIZE_SPECIAL_CHARS))) {
                            showClaim(checkFunctions($db));
                        } else if ($action == "Bekijk Submissies") {
                            showMessage("Jij bent een redacteur!", $id);
                        } else if ($action == "Voeg Tekst Toe") {
                            showMessage("Jij bent een journalist!", $id);
                        } else if ($action == "Upload Foto's") {
                            showMessage("Jij bent een fotograaf!", $id);
                        }
                    }
                }
            }
            ?>
            <div id="background"></div>
        </div>
    </body>
</html>