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
                <h1>Vul In:</h1>
                <p>Vul hier jouw nieuwe wachtwoord en telefoonnummer in</p>
                <form action="RegisterSucceed.php" method="POST">
                    <p><input type="text" name="username" placeholder="B.Voorbeeld@Gemorskos.nl"> :Gebruikersnaam</p>
                    <p><input type="password" name="wachtwoord" placeholder=". . . . . . . . . . ."> :Nieuw wachtwoord</p>
                    <p><input type="text" name="telefoonnummer" placeholder="31-1234567"> :Nieuw telefoonnummer</p>
                    <p><input type="submit" name="submit" value="verzenden">
                </form>
            </div>
        </div>
    </body>
</html>
