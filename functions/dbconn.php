<?php

$serverName = "localhost";
$dbEmail = "root";
$dbPassword = "";
$dbName = "recipe_modifier";

$conn = mysqli_connect($serverName, $dbEmail, $dbPassword, $dbName);

if (!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}