
<?php

session_start();

if (!isset($_SESSION["msg"])) {
    $_SESSION['msg'] = 'Here will be your message!';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <title>DOPYSK 4</title>
</head>

<body>

    <!-- <form action='server.php' method='POST'> -->
        <label>Name:</label>
        <input type='text' name='name' id='name'>
        <label>Password:</label>
        <input type='password' name='password' id='password'>
        <button name='submit-btn' id='submit-btn'>LOG IN</button>
    <!-- </form> -->

    <?php
        echo $_SESSION['msg'];
    ?>

    <script src='script.js'></script>

</body>
</html>