<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style/style.css">
        <script type="text/javascript" src="../script/dropdown.js"></script>
        <title>Home</title>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <a href=".">
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
                    <h1>Je bent al uitgelogd.</h1>
                    <h3>Klik <a href="..">hier</a> om naar de homepagina te gaan</h3>
                </main>';
            } else {
                $host = $_ENV["host"];
                $username = $_ENV["username"];
                $password = $_ENV["password"];
                $database = $_ENV["database"];

                try {
                    $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
                } catch (Exception $exc) {
                    echo '<main id="content">
                        <h1>De database is op dit moment niet bereikbaar. Probeer het later opnieuw.</h1>
                    </main>';
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

                session_destroy();
                echo '<main id="content">
                    <h1>Succesvol uitgelogd.</h1>
                    <h3>Klik <a href="..">hier</a> om naar de homepagina te gaan</h3>
                </main>';
            }
            ?>
        </div>
        <div id="background"></div>
    </body>
</html>