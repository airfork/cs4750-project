<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['username'] && !empty($_POST['pass']))) {
        echo 'Username: ' . $_POST['username'] . ' Pass: ' . $_POST['username'];
    }
} else { ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>THE Library</title>
    </head>
    <body>
        <h2>Login</h2>
        <form method="post" action=<?php echo $_SERVER['PHP_SELF']; ?>>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required>
            <br>
            <label for="pass">Password</label>
            <input type="password" name="pass" id="pass" placeholder="Password" required>
            <br>
            <input type="submit"/>
        </form>
    </body>
    </html>
<?php } ?>