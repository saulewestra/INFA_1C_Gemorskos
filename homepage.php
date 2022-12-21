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
            <div id="background">
                <header>
                    <div id="logo">
                        <h1 id="logoh">Gemorskos</h1>
                        <p id="logop">Wij maken kranten</p>
                    </div>
                    <div id="acc">
                        <img id="acc" src="" alt="acc">
                    </div>
                </header>   
                <?php
                try{
                    $dbConnect = new PDO ("mysql:host=mysql;dbname=Gemorskos;charset=utf8", "root", "qwerty");
                }catch(Exception $error){
                    echo "<main id='content'><h1>Geen verbinding met de database</h1></main>";
                }
                if(isset($dbConnect)){
                    try{
                        $stmt = $dbConnect->prepare("SELECT *
                                                    FROM `Evenement`
                                                    JOIN `Evenement_Detail`
                                                    ON `Evenement`.`evenement_id` = `Evenement_Detail`.`evenement_id`");
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
                            <div>';
                                while($result = $stmt->fetch()) {
                                    echo '<div id="homeblock"
                                        <h1> Overzicht van events </h1>
                                        <a href="events/?id='.$id.'"> "'.$evenementname.'"
                                        </a>
                                        </div>';
                            }
                            echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>
