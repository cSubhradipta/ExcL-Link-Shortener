<?php
    $domain = $_SERVER['SERVER_NAME']."/";
    $tempurl = "localhost/linx/";
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "linxdb";

    $conn = mysqli_connect($server, $username, $password, $database);
    if (!$conn){
        die("Error". mysqli_connect_error());
    }
?>