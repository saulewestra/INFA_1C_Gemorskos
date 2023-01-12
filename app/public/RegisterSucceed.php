<?php
$error = [];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style/style.css">
        <title>Register</title>
    </head>
    <body>
        <div id ="wrapper">
            <header id="header">
                <div id="logo">
                    <h1>Gemorskos</h1>
                    <h3>Wij maken kranten</h3>
                </div>
                <!--Hier moet de path komen om de dropdown te zien-->
                <a href="#"><img id="profile" src="img/profilepic.png" alt="Profile"></a>
            </header>
            <div id="background"></div>
            <div id="event">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (!$username = filter_input(INPUT_POST, "username", FILTER_VALIDATE_EMAIL)) {
                        $error[] = "De ingevoerde gebruikersnaam is onjuist.";
                    }
                    if (!$wachtwoord = filter_input(INPUT_POST, "wachtwoord")) {
                        $error[] = "Het ingevoerde wachtwoord is ongeldig.";
                    }
                    if (!$telefoonnummer = filter_input(INPUT_POST, "telefoonnummer")) {
                        $error[] = "De ingevoerde telefoonnummer is ongeldig.";
                    }
                    if (count($error) == 0) {
                        $dbConnect = null;
                        $host = $_ENV['host'];
                        $username = $_ENV['username'];
                        $password = $_ENV['password'];
                        $database = $_ENV['database'];
                        try {
                            $dbConnect = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
                        } catch(Exception $ex) {
                            echo "<main id='event'><h1>Geen verbinding met de database.</h1></main>";
                        }
                        if ($dbConnect) {
                            try {
                                $stmt = $dbConnect->prepare("SELECT * 
                                                            FROM `Medewerkers` 
                                                            WHERE `email` = :unParam;");
                                $stmt->bindParam("unParam", $username, PDO::PARAM_STR);
                                $stmt->execute();
                            } catch(Exception $ex) {
                                echo "<main id='event'><h1>Query is niet uitgevoerd.</h1></main>";
                            }
                        }
                        if (isset($stmt)) {
                            if ($stmt->rowCount() == 1) {
                                $hashedWachtwoord = password_hash($wachtwoord, PASSWORD_BCRYPT);
                                try {
                                    $stmt = $dbConnect->prepare("UPDATE `Medewerkers`
                                                                SET `telefoonnummer` = :tnParam, `wachtwoord` = :wwParam
                                                                WHERE `email` = :unParam;");
                                    $stmt->bindParam("tnParam", $telefoonnummer, PDO::PARAM_STR);
                                    $stmt->bindParam("unParam", $username, PDO::PARAM_STR);
                                    $stmt->bindParam("wwParam", $hashedWachtwoord, PDO::PARAM_STR);
                                    $stmt->execute();
                                    if ($stmt->rowCount() == 1) {
                                        echo "<main id='event'><h1>Het wachtwoord en de telefoonnummer zijn aangepast.</h1></main>";
                                        //Hier moet de path komen om terug te gaan naar het overzicht scherm
                                        echo "<main id='event'><p><a href='#'>Ga terug naar het werkoverzicht</a></p></main>";
                                    }
                                } catch(Exception $ex) {
                                    echo "<main id='event'><h1>Query is niet uitgevoerd.</h1></main>";
                                }
                            } else {
                                echo "<main id='event'><h1>Deze gebruikersnaam is als in gebruik.</h1></main>";
                            }
                        } else {
                            echo "<main id='event'><h1>Geen verbinding met de database.</h1></main>";
                        }
                    } else {
                        foreach($error as $message);
                        echo $message;
                    }
                } else {
                    echo "<main id='event'><h1>Probeer opnieuw te verbinden met de website</h1></main>";
                    echo "<main id='event'><p><a href='Register.php'>Ga terug</a></p></main>";
                }
                ?>
            </div>
        </div>
    </body>
</html>
