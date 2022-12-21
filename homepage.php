<?php
try{
    $dbConnect = new PDO ("mysql:host=mysql;dbname=Bugdb;charset=utf8", "root", "qwerty");
}catch(Exception $error){
    echo "Geen verbinding met de database";
}
if(isset($dbConnect)){
    try{
        $stmt = $dbConnect->prepare("SELECT *
                                    FROM `bugdb`");
        $stmt->bindColumn("id",$id);
        $stmt->bindColumn("product_name",$product_name);
        $stmt->bindColumn("version",$version);
        $stmt->bindColumn("hardware_type",$hardware_type);
        $stmt->bindColumn("OS",$OS);
        $stmt->bindColumn("frequency",$frequency);
        $stmt->bindColumn("solution",$solution);
        $stmt->execute();                         
    }catch(Exception $error){
        echo $error;
    }
}
?>

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
            <header>
                <div id="logo">
                    <h1 id="logoh">Gemorskos</h1>
                    <p id="logop">Wij maken kranten</p>
                </div>
                <div id="acc">
                    <img id="acc" src="" alt="acc">
                </div>
            </header>
            <div id="blockhome">
                <h1>Test</h1>
                mgwlkjsfkjlsefklsdfkjf
                <?php
                  
                ?>
            </div>
        </div>
    </body>
</html>