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
        <title>Login</title>
    </head>
    <body>
        <div id="wrapper">
            <header id="header">
                <div id="logo">
                    <h1 id="logoh">Gemorskos</h1>
                    <p id="logop">Wij maken kranten</p>
                </div>
                <img id="profile" src="../img/profilepic.png" alt="Profile">
            </header>
            <?php
            function showMessage(string $message): void {
                echo '<main id="content">
                    <h1>'.$message.'</h1>
                    <h3>Klik <a href=".">hier</a> om terug te gaan</h3>
                </main>';
            }

            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (!isset($_SESSION["id"])) {
                    echo '<form id="login" method="POST">
                        <div id="loginBackground"></div>
                        <div id="loginBackground2"></div>
                        <h1>Gemorskos</h1>
                        <input type="text" name="firstname" placeholder="Voornaam">
                        <input type="text" name="surname" placeholder="Achternaam">
                        <input type="password" name="password" placeholder="Wachtwoord">
                        <input type="submit" value="Login">
                    </form>';
                } else {
                    echo '<main id="content">
                        <h1>Je bent al ingelogd.</h1>
                        <h3>Klik <a href="..">hier</a> om naar de homepagina te gaan</h3>
                    </main>';
                }
            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $host = $_ENV['host'];
                $dbUsername = $_ENV['username'];
                $dbPassword = $_ENV['password'];
                $database = $_ENV['database'];
                if (!($firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_SPECIAL_CHARS))) {
                    showMessage("Onjuiste inloggegevens.");
                } else if (!($surname = filter_input(INPUT_POST, "surname", FILTER_SANITIZE_SPECIAL_CHARS))) {
                    showMessage("Onjuiste inloggegevens.");
                } else if (!($password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS))) {
                    showMessage("Onjuiste inloggegevens.");
                } else {
                    $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $dbUsername, $dbPassword);
                    $cursor = $db->prepare("SELECT medewerker_id, wachtwoord FROM Medewerkers WHERE voornaam = :firstname AND achternaam = :surname");
                    $cursor->bindParam("firstname", $firstname);
                    $cursor->bindParam("surname", $surname);
                    $cursor->execute();
                    if ($cursor->rowCount() == 0) {
                        showMessage("Onjuiste inloggegevens.");
                    } else {
                        $result = $cursor->fetch(PDO::FETCH_NUM);
                        var_dump($result);
                        echo $password;
                        if (!password_verify($password, $result[1])) {
                            showMessage("Onjuiste inloggegevens.");
                        } else {
                            $_SESSION["id"] = $result[0];
                            echo '<main id="content">
                                <h1>Succesvol ingelogd.</h1>
                                <h3>Klik <a href="..">hier</a> om naar de homepagina te gaan</h3>
                            </main>';
                        }
                    }
                }
            }
            ?>
        </div>
        <div id="background"></div>
    </body>
</html>