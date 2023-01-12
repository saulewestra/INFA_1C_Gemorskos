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
        <link rel="stylesheet" href="style/style.css">
        <title>Home</title>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <div id="logo">
                    <h1 id="logoh">Gemorskos</h1>
                    <p id="logop">Wij maken kranten</p>
                </div>
                <img id="profile" src="img/profilepic.png" alt="Profile">
            </header>   
            <?php
            if (!isset($_SESSION["id"])) {
                echo '<main id="content">
                    <h1>Je bent niet ingelogd.</h1>
                    <h3>Klik <a href="login">hier</a> om naar de inlogpagina te gaan</h3>
                </main>';
            } else {
                $employeeId = $_SESSION["id"];
                $host = $_ENV["host"];
                $username = $_ENV["username"];
                $password = $_ENV["password"];
                $database = $_ENV["database"];

                try {
                    $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
                } catch(Exception $exc) {
                    echo '<main id="content">
                        <h1>De database is op dit moment niet bereikbaar. Probeer het later opnieuw.</h1>
                    </main>';
                }

                if (isset($db)) {
                    try {
                        $cursor = $db->prepare("SELECT evenement_naam, beschrijving, redacteur_id, journalist_id, fotograaf_id FROM Evenement");
                        $cursor->execute();
                        if ($cursor->rowCount() == 0) {
                            echo '<main id="content"><h1>Er zijn geen events beschikbaar op dit moment.</h1><main>';
                        } else {
                            echo '<div id="eventposition">
                                <h1>Overzicht van Events</h1>';
                            while($event = $cursor->fetch(PDO::FETCH_ASSOC)) {
                                echo '<a href="event/?id='.$id.'" id="event">
                                    <h1>'.$event["evenement_naam"].'</h1>
                                    <p>'.$event["beschrijving"].'</p>
                                </a>';
                            }
                            echo '</div>';
                        }
                        $cursor->closeCursor();
                    } catch(Exception $exc) {
                        echo '<main id="content"><h1>Er zijn geen events beschikbaar op dit moment.</h1><main>';
                    }
                }
            }
            ?>
        </div>
        <div id="background"></div>
    </body>
</html>