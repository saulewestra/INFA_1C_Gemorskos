<?php

//variables 
$host = 'mysql';
$username = 'root';
$password = 'qwerty';
$database = 'Login';


$db = new PDO("mysql:host=$host;dbname=$database", $username, $password);


if (isset($_POST['submit'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];


    $query = 'SELECT * FROM users WHERE username = :username AND password = :password';
    $params = [
      'username' => $username,
      'password' => $password
    ];


    $stmt = $db->prepare($query);
    $stmt->execute($params);

    $result = $stmt->fetch();


    if ($result) {

        session_start();

        $_SESSION['user'] = $result;


        header('Location: https://google.com');
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
                    <a href="https://www.google.com"><img src="img/profilepic.png" alt="ProfilePic"></a>
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
                                <input type="text" name="username" value="" placeholder="Email/Username" required />
                            </div>

                            <div>
                                <input type="password" name="password" value="" placeholder="Password" required />
                            </div>

                            <div>
                                <input type="submit" name="submit" value="Login" class="input2" />
                            </div>
                            <div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>