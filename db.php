<?php
    $host = 'localhost';
    $user = 'root';
    $pass = '2411Oleg';
    $db_name = 'guestBookDB';
    $link = mysqli_connect($host, $user, $pass, $db_name);

    if (!$link)
    {
        echo mysqli_connect_error();
        exit;
    }

?>