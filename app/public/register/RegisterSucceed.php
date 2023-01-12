<?php
$error = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!$wachtwoord = filter_input(INPUT_POST, "wachtwoord")) {
        $error[] = "Het ingevoerde wachtwoord is ongeldig.";
    }
    if (!$telefoonnummer = filter_input(INPUT_POST, "telefoonnummer", FILTER_SANITIZE_NUMBER_INT)) {
        $error[] = "De ingevoerde telefoonnummer is ongeldig.";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../style/style.css">
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
                <a href="#"><img id="profile" src="../img/profilepic.png" alt="Profile"></a>
            </header>
            <div id="background"></div>
            <div id="event">
                <?php
                    if (count($error) == 0) {
                        $dbConnect = null;
                        $host = $_ENV['host'];
                        $username = $_ENV['username'];
                        $password = $_ENV['password'];
                        $database = $_ENV['database'];
                        try {
                            $dbConnect = new PDO("mysql:host=$host;dbname=$username;charset=utf8", $username, $password);
                        } catch(Exception $ex) {
                            echo "<main id='event'><h1>Geen verbinding met de database</h1></main>";
                        }
                        if ($dbConnect) {
                            
                        }
                    } else {
                        foreach($error as $message);
                        echo $message;
                    }
                ?>
            </div>
        </div>
    </body>
</html>
<?php
} else {
 echo "Probeer opnieuw te verbinden.";
}
?>