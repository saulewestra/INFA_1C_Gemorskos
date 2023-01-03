<?php
session_start();
//variables
$host = 'mysql';
$username = 'root';
$password = 'qwerty';
$database = 'Gemorskos';


$db = new PDO("mysql:host=$host;dbname=$database", $medewerker_id, $wachtwoord);

if ($_SESSION['LoggedIn']){

    header('Location: Index.html');

}

if (isset($_POST['submit'])) {

    $username = $_POST['medewerker_id'];
    $password = $_POST['wachtwoord'];


    $query = 'SELECT * FROM Medewerkers WHERE medewerker_id = :medewerker_id ';
    $params = [
      'medewerker_id' => $medewerker_id
    ];


    $stmt = $db->prepare($query);
    $stmt->execute($params);

    $result = $stmt->fetch();
    $hash_wachtwoord = $result['medewerker_id'];



    if (password_verify($wachtwoord, $hash_wachtwoord)) {

        session_start();

        $_SESSION['medewerker_id'] = $medewerker_id;
        $_SESSION['LoggedIn'] = true;


        header('Location: Index.html');
        exit;
    } else {

        $error = 'Invalid username or password';

    }
}

?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Germorskos</title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
</head>
<body>



    <div id="Container">
        <header>
            <div class="logo">
                <h1>Gemorskos</h1>
                <p>Wij maken kranten</p>
            </div>
            <div class="profile">
                <a href="https://www.google.com">
                    <img src="img/profilepic.png" alt="ProfilePic" />
                </a>
            </div>

        </header>
        <div class="Main">
            <img src="img/background.png" alt="Background" />
        </div>
        <div class="login">
            <div>
                <div>
                    <h1>Gemorskos</h1>

                    <div>

                        <form action="" method="post">
                            <p>Login</p>
                            <div>
                                <input type="text" name="medewerker_id" value="" placeholder="Medewerker ID" required />
                            </div>

                            <div>
                                <input type="password" name="wachtwoord" value="" placeholder="Wachtwoord" required />
                            </div>

                            <div>
                                <input type="submit" name="submit" value="Login" class="input2" />
                            </div>
                            <div></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>