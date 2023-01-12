<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <title>Homepage</title>
    </head>
    <body>
        <div id="container">
            <div id="background"></div>
            <header id="header">
                <div id="logo">
                    <h1 id="logoh">Gemorskos</h1>
                    <p id="logop">Wij maken kranten</p>
                </div>
                <div class="profile">
                    <img id="acc" src="img/profilepic.png" alt="acc">
                </div>
            </header>   
            <?php
            $host = $_ENV['host'];
            $username = $_ENV['username'];
            $password = $_ENV['password'];
            $database = $_ENV['database'];
            try{
                $dbConnect = new PDO ("mysql:host=$host;dbname=$username;charset=utf8", $username, $password);
            }catch(Exception $error){
                echo "<main id='event'><h1>Geen verbinding met de database</h1></main>";
            }
            if(isset($dbConnect)){
                try{
                    $stmt = $dbConnect->prepare("SELECT *
                                                FROM `Evenement`");
                    $stmt->bindColumn("evenement_id",$id);
                    $stmt->bindColumn("evenement_naam",$evenementname);
                    $stmt->bindColumn("beschrijving",$beschrijving);
                    $stmt->bindColumn("datum",$datum);
                    $stmt->bindColumn("straatnaam",$straatnaam);
                    $stmt->bindColumn("stad",$stad);
                    $stmt->bindColumn("postcode",$solution);
                    $stmt->bindColumn("redacteur_id",$redacteurid);
                    $stmt->bindColumn("journalist_id",$journalistid);
                    $stmt->bindColumn("fotograaf_id",$fotograafid);
                    $stmt->execute();                         
                }catch(Exception $error){
                    echo "<main id='content'><h1>Tabel niet gevonden</h1><main>";
                }
                if(isset($stmt)) {
                    echo '   
                        <div id="eventposition">
                        <h1> Overzicht van Events</h1>';
                            while($result = $stmt->fetch()) {
                                echo '<a href="events/?id='.$id.'" id="event">
                                    <h1>'.$evenementname.'</h1>
                                    <p>'.$beschrijving.'</p>
                                </a>';
                                    
                        }
                        echo '</div>';
                }
            }
            ?>
        </div>
    </body>
</html>
