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
        <title>Wijzig Wachtwoord</title>
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

            if (!isset($_SESSION["id"])) {
                echo '<main id="content">
                    <h1>Je bent niet ingelogd.</h1>
                    <h3>Klik <a href="../login">hier</a> om naar de inlogpagina te gaan</h3>
                </main>';
            } else if ($_SERVER["REQUEST_METHOD"] == "GET") {
                echo '<form id="login" method="POST">
                    <div id="loginBackground"></div>
                    <div id="loginBackground2"></div>
                    <h1>Wijzig Wachtwoord</h1>
                    <input type="password" name="oldpassword" placeholder="Oud Wachtwoord">
                    <input type="password" name="newpassword" placeholder="Nieuw Wachtwoord">
                    <input type="password" name="newpasswordrepeat" placeholder="Herhaal Nieuw Wachtwoord">
                    <input type="submit" value="Wijzig Wachtwoord">
                </form>';
            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $host = $_ENV['host'];
                $dbUsername = $_ENV['username'];
                $dbPassword = $_ENV['password'];
                $database = $_ENV['database'];
                $employeeId = $_SESSION["id"];
                if (!($oldPassword = filter_input(INPUT_POST, "oldpassword", FILTER_SANITIZE_SPECIAL_CHARS))) {
                    showMessage("Onjuiste gegevens.");
                } else if (!($newPassword = filter_input(INPUT_POST, "newpassword", FILTER_SANITIZE_SPECIAL_CHARS))) {
                    showMessage("Onjuiste gegevens.");
                } else if (!($newPasswordRepeat = filter_input(INPUT_POST, "newpasswordrepeat", FILTER_SANITIZE_SPECIAL_CHARS))) {
                    showMessage("Onjuiste gegevens.");
                } else if ($newPassword != $newPasswordRepeat) {
                    showMessage("Wachtwoorden komen niet overeen.");
                } else {
                    try {
                        $db = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $dbUsername, $dbPassword);
                        $cursor = $db->prepare("SELECT wachtwoord FROM Medewerkers WHERE medewerker_id = :id");
                        $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                        $cursor->execute();
                        if ($cursor->rowCount() == 0) {
                            showMessage("Er is iets fout gegaan tijdens het wijzigen van uw wachtwoord. Probeer het later opnieuw.");
                        } else {
                            $result = $cursor->fetch(PDO::FETCH_NUM);
                            if (!password_verify($oldPassword, $result[0])) {
                                showMessage("Onjuiste gegevens.");
                            } else {
                                $cursor->closeCursor();
                                $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
                                $cursor = $db->prepare("UPDATE Medewerkers SET wachtwoord = :newpassword WHERE medewerker_id = :id");
                                $cursor->bindParam("newpassword", $newPasswordHash);
                                $cursor->bindParam("id", $employeeId, PDO::PARAM_INT);
                                $cursor->execute();
                                echo '<main id="content">
                                    <h1>Wachtwoord succesvol gewijzigd.</h1>
                                    <h3>Klik <a href="..">hier</a> om naar de homepagina te gaan</h3>
                                </main>';
                            }
                        }
                        $cursor->closeCursor();
                    } catch (Exception $exc) {
                        showMessage("Er is iets fout gegaan tijdens het wijzigen van uw wachtwoord. Probeer het later opnieuw.");
                    }
                }
            }
            ?>
            
        </div>
        <div id="background"></div>
    </body>
</html>