<?php
if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            echo '<form method="POST">
                <input type="submit">
            </form>';
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_SESSION["id"])) {
                echo 'You are already logged in';
            } else {
                $_SESSION["id"] = 123;
                echo 'Succesfully logged in';
            }
        }
        ?>
        
    </body>
</html>