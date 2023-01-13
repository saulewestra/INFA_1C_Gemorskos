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
        <link rel="stylesheet" href="../../style/style.css">
        <script type="text/javascript" src="../../script/dropdown.js"></script>
        <title>Nieuw Event</title>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <a href="../..">
                    <div id="logo">
                        <h1 id="logoh">Gemorskos</h1>
                        <p id="logop">Wij maken kranten</p>
                    </div>
                </a>
                <img id="profile" src="../../img/profilepic.png" alt="Profile">
            </header>
            <?php
            function loggedOut(): void {
                echo '<div id="dropdown" style="display: none;">
                    <h2>U bent niet ingelogd.</h2>
                    <ul>
                        <li><a href="../../login">Inloggen</a></li>
                    </ul>
                </div>';
            }

            function loggedIn(string $firstname, string $surname): void {
                echo '<div id="dropdown" style="display: none;">
                    <h2>'.$firstname.' '.$surname.'</h2>
                    <ul>
                        <li><a href="../../changepassword">Wachtwoord Wijzigen</a></li>
                        <li><a href="../../logout">Uitloggen</a></li>
                    </ul>
                </div>';
            }

            if (!isset($_SESSION["id"])) {
                loggedOut();
                echo '<main id="content">
                    <h1>Je bent niet ingelogd.</h1>
                    <h3>Klik <a href="../../login">hier</a> om naar de inlogpagina te gaan</h3>
                </main>';
            } else {
                $host = $_ENV["host"];
                $username = $_ENV["username"];
                $password = $_ENV["password"];
                $database = $_ENV["database"];
                $employeeId = $_SESSION["id"];

                try {
                    $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
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

                function showMessage(string $message): void {
                    echo '<main id="content">
                        <h1>'.$message.'</h1>
                        <h3>Klik <a href=".">hier</a> om terug te gaan</h3>
                    </main>';
                }

                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    echo '<form id="login" method="POST">
                        <div id="loginBackground"></div>
                        <div id="loginBackground2"></div>
                        <h1>Nieuw Event</h1>
                        <input type="text" name="name" placeholder="Naam">
                        <textarea name="description" placeholder="Beschrijving"></textarea>
                        <input type="text" name="street" placeholder="Straatnaam">
                        <input type="text" name="city" placeholder="Stad">
                        <input type="text" name="zipcode" placeholder="Postcode">
                        <input type="submit" value="Toevoegen">
                    </form>';
                } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        $cursor = $db->prepare("SELECT werk_functie_id FROM Medewerkers WHERE medewerker_id = :id");
                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                        $cursor->execute();
                        $workFunction = $cursor->fetch(PDO::FETCH_NUM);
                        $cursor->closeCursor();
                        if ($workFunction[0] != 1) {
                            showMessage("U heeft niet de rechten om dit event toe te voegen.");
                        } else {
                            if (!($name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS)) || strlen($name) > 40) {
                                showMessage("Onjuiste naam.");
                            } else if (!($description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS))) {
                                showMessage("Onjuiste beschrijving.");
                            } else if (!($street = filter_input(INPUT_POST, "street", FILTER_SANITIZE_SPECIAL_CHARS)) || strlen($street) > 26) {
                                showMessage("Onjuiste straatnaam.");
                            } else if (!($city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_SPECIAL_CHARS)) || strlen($city) > 40) {
                                showMessage("Onjuiste stad.");
                            } else if (!($zipcode = filter_input(INPUT_POST, "zipcode", FILTER_SANITIZE_SPECIAL_CHARS)) || strlen($zipcode) > 6) {
                                showMessage("Onjuiste postcode.");
                            } else {
                                $date = date('Y-m-d H:i:s');
                                $cursor = $db->prepare("INSERT INTO Evenement(evenement_naam, beschrijving, dag, tijd, straatnaam, stad, postcode) VALUES(:name, :description, :date, :date, :street, :city, :zipcode); INSERT INTO Evenement_Detail(evenement_id) VALUES (LAST_INSERT_ID())");
                                $cursor->bindParam("name", $name);
                                $cursor->bindParam("description", $description);
                                $cursor->bindParam("date", $date);
                                $cursor->bindParam("street", $street);
                                $cursor->bindParam("city", $city);
                                $cursor->bindParam("zipcode", $zipcode);
                                $cursor->execute();
                                $cursor->closeCursor();
                                showMessage("Event toegevoegd.");
                            }
                        }
                    } catch (Exception $exc) {
                        showMessage("Er is iets fout gegaan tijdens het toevoegen van dit event.");
                    }
                }
            }
            ?>
        </div>
        <div id="background"></div>
    </body>
</html>