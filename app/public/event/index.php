<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../style/style.css">
        <script type="text/javascript" src="../script/dropdown.js"></script>
        <title>Event</title>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <a href="..">
                    <div id="logo">
                        <h1 id="logoh">Gemorskos</h1>
                        <p id="logop">Wij maken kranten</p>
                    </div>
                </a>
                <img id="profile" src="../img/profilepic.png" alt="Profile">
            </header>
            <?php
            function loggedOut(): void {
                echo '<div id="dropdown" style="display: none;">
                    <h2>U bent niet ingelogd.</h2>
                    <ul>
                        <li><a href="../login">Inloggen</a></li>
                    </ul>
                </div>';
            }

            function loggedIn(string $firstname, string $surname): void {
                echo '<div id="dropdown" style="display: none;">
                    <h2>'.$firstname.' '.$surname.'</h2>
                    <ul>
                        <li><a href="../changepassword">Wachtwoord Wijzigen</a></li>
                        <li><a href="../logout">Uitloggen</a></li>
                    </ul>
                </div>';
            }

            if (!isset($_SESSION["id"])) {
                loggedOut();
                echo '<main id="content">
                    <h1>Je bent niet ingelogd.</h1>
                    <h3>Klik <a href="../login">hier</a> om naar de inlogpagina te gaan</h3>
                </main>';
            } else {
                $host = $_ENV["host"];
                $dbUsername = $_ENV["username"];
                $dbPassword = $_ENV["password"];
                $database = $_ENV["database"];
                $employeeId = $_SESSION["id"];

                try {
                    $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $dbUsername, $dbPassword);
                } catch (Exception $exc) {
                    showMessage("De database is op dit moment niet bereikbaar. Probeer het later nog eens.");
                }

                if (isset($db)) {
                    try {
                        $cursor = $db->prepare("SELECT voornaam, achternaam FROM Medewerkers WHERE medewerker_id = :id");
                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                        $cursor->execute();
                        if ($cursor->rowCount() == 0) {
                            loggedOut();
                        } else {
                            $names = $cursor->fetch(PDO::FETCH_NUM);
                            loggedIn($names[0], $names[1]);
                        }
                        $cursor->closeCursor();
                    } catch (Exception $exc) {}
                }

                function showMessage(string $message, int $id = null): void {
                    $href = (isset($id)) ? "./?id=".$id : ".";
                    echo '<main id="content">
                        <h1>'.$message.'</h1>
                        <h3>Klik <a href="'.$href.'">hier</a> om terug te gaan</h3>
                    </main>';
                }

                function showEvent(array $event, array $claims, array $functions): void {
                    echo '<main id="event">
                        <h1>'.$event["evenement_naam"].'</h1>
                        <p>'.$event["beschrijving"].'</p>
                        <h4>Datum: '.$event["dag"].' '.$event["tijd"].'</h4>
                        <h4>Straat: '.$event["straatnaam"].'</h4>
                        <h4>Stad: '.$event["stad"].'</h4>
                        <h4>Postcode: '.$event["postcode"].'</h4>
                        <div id="buttons">
                        <form method="POST">
                    ';
                    if ($functions["headredactor"]) {
                        echo '<input type="submit" name="action" value="Verwijder Event">';
                    }
                    if (!$claims["redactor"] || !$claims["journalist"] || !$claims["photographer"]) {
                        echo '<input type="submit" value="Claim">';
                    }
                    if ($claims["redactor"]) {
                        echo '<input type="submit" name="action" value="Bekijk Submissies">';
                    }
                    if ($claims["journalist"]) {
                        echo '<input type="submit" name="action" value="Voeg Tekst Toe">';
                    }
                    if ($claims["photographer"]) {
                        echo '<input type="submit" name="action" value="Upload Foto\'s">';
                    }
                    echo '</form>
                        </div>
                    </main>';
                }

                function checkClaims(PDO $db, int $id): array {
                    global $employeeId;
                    try {
                        $cursor = $db->prepare("SELECT Evenement_Detail.redacteur_id, Evenement_Detail.journalist_id, Evenement_Detail.fotograaf_id FROM Evenement_Detail JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id WHERE Evenement_Detail.evenement_id = :id");
                        $cursor->bindParam("id", $id, PDO::PARAM_INT);
                        $cursor->execute();
                        $result = $cursor->fetch(PDO::FETCH_ASSOC);
                        $cursor->closeCursor();
                        if (!$result) {
                            return ["redactor" => false, "journalist" => false, "photographer" => false];
                        } else {
                            return ["redactor" => $result["redacteur_id"] == $employeeId, "journalist" => $result["journalist_id"] == $employeeId, "photographer" => $result["fotograaf_id"] == $employeeId];
                        }
                    } catch (Exception $exc) {
                        return ["redactor" => false, "journalist" => false, "photographer" => false];
                    }
                }

                function getEvent(PDO $db, int $id): array | false {
                    try {
                        $cursor = $db->prepare("SELECT Evenement.evenement_naam, Evenement.beschrijving, Evenement.dag, Evenement.tijd, Evenement.straatnaam, Evenement.stad, Evenement.postcode, Evenement_Detail.redacteur_id, Evenement_Detail.journalist_id, Evenement_Detail.fotograaf_id FROM Evenement JOIN Evenement_Detail ON Evenement.evenement_id = Evenement_Detail.evenement_id WHERE Evenement.evenement_id = :id");
                        $cursor->bindParam("id", $id, PDO::PARAM_INT);
                        $cursor->execute();
                        $result = $cursor->fetch(PDO::FETCH_ASSOC);
                        $cursor->closeCursor();
                        if (!$result) {
                            return false;
                        } else {
                            return $result;
                        }
                    } catch (Exception $exc) {
                        echo $exc;
                        return false;
                    }
                }

                function claimEvent(PDO $db, int $id, bool $redacteur, bool $journalist, bool $fotograaf): void {
                    global $employeeId;
                    if (!isset($_SESSION["id"])) {
                        echo '<main id="content">
                            <h1>Je bent niet ingelogd.</h1>
                            <h3>Klik <a href="../login">hier</a> om naar de inlogpagina te gaan</h3>
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
                                    $cursor = $db->prepare("SELECT Evenement_Detail.redacteur_id FROM Evenement_Detail JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id WHERE Evenement_Detail.evenement_id = :id");
                                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                                    $cursor->execute();
                                    $result = $cursor->fetch();
                                    $cursor->closeCursor();
                                    if ($result && $result["redacteur_id"]) {
                                        showMessage("Dit event heeft al een redacteur.", $id);
                                        return;
                                    } else {
                                        $cursor = $db->prepare("UPDATE Evenement_Detail SET redacteur_id = :id WHERE evenement_id = :event_id");
                                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                                        $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                        $cursor->execute();
                                        $cursor->closeCursor();
                                    }
                                }
                                if ($journalist) {
                                    $cursor = $db->prepare("SELECT Evenement_Detail.journalist_id FROM Evenement_Detail JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id WHERE Evenement_Detail.evenement_id = :id");
                                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                                    $cursor->execute();
                                    $result = $cursor->fetch();
                                    $cursor->closeCursor();
                                    if ($result && $result["journalist_id"]) {
                                        showMessage("Dit event heeft al een journalist.", $id);
                                        return;
                                    } else {
                                        $cursor = $db->prepare("UPDATE Evenement_Detail SET journalist_id = :id WHERE evenement_id = :event_id");
                                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                                        $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                        $cursor->execute();
                                        $cursor->closeCursor();
                                    }
                                }
                                if ($fotograaf) {
                                    $cursor = $db->prepare("SELECT Evenement_Detail.fotograaf_id FROM Evenement_Detail JOIN Evenement ON Evenement_Detail.evenement_id = Evenement.evenement_id WHERE Evenement_Detail.evenement_id = :id");
                                    $cursor->bindParam("id", $id, PDO::PARAM_INT);
                                    $cursor->execute();
                                    $result = $cursor->fetch();
                                    $cursor->closeCursor();
                                    if ($result && $result["fotograaf_id"]) {
                                        showMessage("Dit event heeft al een fotograaf.", $id);
                                        return;
                                    } else {
                                        $cursor = $db->prepare("UPDATE Evenement_Detail SET fotograaf_id = :id WHERE evenement_id = :event_id");
                                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                                        $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                        $cursor->execute();
                                        $cursor->closeCursor();
                                    }
                                }
                                showMessage("Rollen succesvol geclaimed.", $id);
                            }
                        } catch (Exception $exc) {
                            showMessage("Er is iets fout gegaan. Probeer het later opnieuw.", $id);
                        }
                    }
                }

                function checkFunctions(PDO $db): array {
                    global $employeeId;
                    try {
                        $cursor = $db->prepare("SELECT werk_functie_id FROM Medewerkers WHERE medewerker_id = :id");
                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                        $cursor->execute();
                        $workFunction = $cursor->fetch(PDO::FETCH_NUM);
                        $cursor->closeCursor();
                        if (!$workFunction) {
                            return ["redactor" => false, "journalist" => false, "photographer" => false];
                        } else {
                            $functions = [];
                            $functions["headredactor"] = $workFunction[0] == 1;
                            $functions["redactor"] = $workFunction[0] == 2;
                            $functions["journalist"] = $workFunction[0] == 3 || $workFunction[0] == 6;
                            $functions["photographer"] = $workFunction[0] == 4 || $workFunction[0] == 6;
                            return $functions;
                        }
                    } catch (Exception $exc) {
                        showMessage("Er is iets fout gegaan. Probeer het later opnieuw.");
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
                            <input id="submitclaim" type="submit" value="Claim">
                        </form>
                    </main>';
                }

                function redactorPage(PDO $db, int $id): void {
                    if (!checkFunctions($db)["redactor"]) {
                        showMessage("U heeft niet voldoende rechten om submissies te bekijken.", $id);
                    } else {
                        try {
                            $cursor = $db->prepare("SELECT Medewerkers.voornaam, Medewerkers.achternaam, Bestand.bestandsnaam, Bestand.bestand_type, Bestand.upload_datum, Bestand.beschrijving FROM Bestand JOIN Medewerkers ON Bestand.medewerker_id = Medewerkers.medewerker_id WHERE Bestand.evenement_id = :id ORDER BY upload_datum DESC");
                            $cursor->bindParam("id", $id, PDO::PARAM_INT);
                            $cursor->execute();
                            if ($cursor->rowCount() == 0) {
                                showMessage("Er zijn nog geen submissies toegevoegd.", $id);
                            } else {
                                echo '<main id="submissions">';
                                while ($submission = $cursor->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<div class="submission">';
                                    if ($submission["bestand_type"] != "text") {
                                        echo '<img src="../files/'.$submission["bestandsnaam"].'" alt="'.$submission["bestandsnaam"].'">';
                                    }
                                    echo '<p>'.$submission["beschrijving"].'</p>
                                        <p class="footerText">Geüpload door '.$submission["voornaam"].' '.$submission["achternaam"].' op '.$submission["upload_datum"].'</p>
                                    </div>';
                                }
                            }
                            $cursor->closeCursor();
                        } catch (Exception $exc) {
                            showMessage("Er is iets fout gegaan. Probeer het later opnieuw.", $id);
                        }
                    }
                }

                function journalistPage(PDO $db, int $id): void {
                    if (!checkFunctions($db)["journalist"]) {
                        showMessage("U heeft niet voldoende rechten om tekst toe te voegen.", $id);
                    } else {
                        echo '<main id="event">
                            <form method="POST">
                                <div id="uploadButton">
                                    <textarea id="uploadText" name="text" placeholder="Text"></textarea>
                                    <input id="textSubmit" type="submit" name="action" value="Add Text">
                                </div>
                            </form>
                        </main>';
                    }
                }

                function uploadPage(PDO $db, int $id): void {
                    if (!checkFunctions($db)["photographer"]) {
                        showMessage("U heeft niet voldoende rechten om bestanden te uploaden.", $id);
                    } else {
                        echo '<main id="event">
                            <form method="POST" enctype="multipart/form-data">
                                <div id="uploadButton">
                                    <input id="fileUpload" type="file" name="file">
                                    <div id="fileButton">Kies een bestand</div>
                                    <textarea id="uploadDescription" name="description" placeholder="Beschrijving"></textarea>
                                    <input id="uploadSubmit" type="submit" name="action" value="Upload">
                                </div>
                            </form>
                        </main>';
                    }
                }

                function uploadFile(PDO $db, int $id): void {
                    global $employeeId;
                    if (!checkFunctions($db)["photographer"]) {
                        showMessage("U heeft niet voldoende rechten om bestanden te uploaden.", $id);
                    } else {
                        if (!is_dir("../files")) {
                            mkdir("../files");
                        }
                        if (!isset($_FILES["file"])) {
                            showMessage("U heeft geen bestanden toegevoegd.", $id);
                        } else if ($_FILES["file"]["error"] != 0) {
                            showMessage("Er is iets fout gegaan tijdens het uploaden.", $id);
                        } else if (explode("/", finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES["file"]["tmp_name"]), 2)[0] != "image") {
                            showMessage("Bestand moet een afbeelding zijn.", $id);
                        } else if (strlen($_FILES["file"]["name"]) > 200) {
                            showMessage("Bestandsnaam kan niet langer dan 200 karakters zijn.", $id);
                        } else if (file_exists("../files/".$_FILES["file"]["name"])) {
                            showMessage("Dit bestand bestaat al.", $id);
                        } else if (!move_uploaded_file($_FILES["file"]["tmp_name"], "../files/".$_FILES["file"]["name"])) {
                            showMessage("Er is iets fout gegaan tijdens het uploaden.", $id);
                        } else {
                            try {
                                $filename = $_FILES["file"]["name"];
                                $filesize = $_FILES["file"]["size"];
                                $filetype = explode("/", finfo_file(finfo_open(FILEINFO_MIME_TYPE), "../files/".$_FILES["file"]["name"]))[0];
                                $uploadDate = date('Y-m-d H:i:s');
                                $description = (isset($_POST["description"])) ? $_POST["description"] : "";
                                $cursor = $db->prepare("INSERT INTO Bestand(medewerker_id, evenement_id, bestandsnaam, bestand_grootte_bytes, bestand_type, upload_datum, beschrijving) VALUES(:employee_id, :event_id, :filename, :filesize, :filetype, :upload_date, :description)");
                                $cursor->bindParam("employee_id", $employeeId, PDO::PARAM_INT);
                                $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                $cursor->bindParam("filename", $filename);
                                $cursor->bindParam("filesize", $filesize, PDO::PARAM_INT);
                                $cursor->bindParam("filetype", $filetype);
                                $cursor->bindParam("upload_date", $uploadDate);
                                $cursor->bindParam("description", $description);
                                $cursor->execute();
                                $cursor->closeCursor();
                                showMessage("Bestand succesvol geüpload.", $id);
                            } catch (Exception $exc) {
                                unlink("../files/".$_FILES["file"]["name"]);
                                showMessage("Er is iets fout gegaan tijdens het uploaden.", $id);
                            }
                        }
                    }
                }

                function addText(PDO $db, int $id): void {
                    global $employeeId;
                    if (!checkFunctions($db)["journalist"]) {
                        showMessage("U heeft niet voldoende rechten om bestanden te uploaden.", $id);
                    } else {
                        if (!isset($_POST["text"]) || strlen($_POST["text"]) == 0) {
                            showMessage("Tekst kan niet leeg zijn.", $id);
                        } else {
                            try {
                                $filename = "";
                                $filesize = 0;
                                $filetype = "text";
                                $uploadDate = date('Y-m-d H:i:s');
                                $description = $_POST["text"];
                                $cursor = $db->prepare("INSERT INTO Bestand(medewerker_id, evenement_id, bestandsnaam, bestand_grootte_bytes, bestand_type, upload_datum, beschrijving) VALUES(:employee_id, :event_id, :filename, :filesize, :filetype, :upload_date, :description)");
                                $cursor->bindParam("employee_id", $employeeId, PDO::PARAM_INT);
                                $cursor->bindParam("event_id", $id, PDO::PARAM_INT);
                                $cursor->bindParam("filename", $filename);
                                $cursor->bindParam("filesize", $filesize, PDO::PARAM_INT);
                                $cursor->bindParam("filetype", $filetype);
                                $cursor->bindParam("upload_date", $uploadDate);
                                $cursor->bindParam("description", $description);
                                $cursor->execute();
                                $cursor->closeCursor();
                                showMessage("Tekst toegevoegd.", $id);
                            } catch (Exception $exc) {
                                showMessage("Er is iets fout gegaan tijdens het toevoegen.", $id);
                            }
                        }
                    }
                }

                function deleteEvent(PDO $db, int $id): void {
                    if (!checkFunctions($db)["headredactor"]) {
                        showMessage("U heeft niet voldoende rechten om dit event te verwijderen.", $id);
                    } else {
                        try {
                            $cursor = $db->prepare("SELECT bestandsnaam FROM Bestand WHERE evenement_id = :id");
                            $cursor->bindParam("id", $id, PDO::PARAM_INT);
                            $cursor->execute();
                            while ($file = $cursor->fetch(PDO::FETCH_NUM)) {
                                if (file_exists("../files/".$file[0])) {
                                    unlink("../files/".$file[0]);
                                }
                            }
                            $cursor->closeCursor();
                            $cursor = $db->prepare("DELETE FROM Evenement WHERE evenement_id = :id");
                            $cursor->bindParam("id", $id, PDO::PARAM_INT);
                            $cursor->execute();
                            $cursor->closeCursor();
                            showMessage("Event verwijderd.");
                        } catch (Exception $exc) {
                            showMessage("Er is iets fout gegaan tijdens het verwijderen.", $id);
                        }
                    }
                }

                if (isset($db)) {
                    if (!($id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)) || !$event = getEvent($db, $id)) {
                        showMessage("Dit event bestaat niet.");
                    } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                        showEvent($event, checkClaims($db, $id), checkFunctions($db));
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
                            redactorPage($db, $id);
                        } else if ($action == "Voeg Tekst Toe") {
                            journalistPage($db, $id);
                        } else if ($action == "Upload Foto&#39;s") {
                            uploadPage($db, $id);
                        } else if ($action == "Upload") {
                            uploadFile($db, $id);
                        } else if ($action == "Add Text") {
                            addText($db, $id);
                        } else if ($action == "Verwijder Event") {
                            deleteEvent($db, $id);
                        }
                    }
                }
            }
            ?>
            <div id="background"></div>
        </div>
    </body>
</html>