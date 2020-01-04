<?php

$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'db_mata';
$koneksi    = mysqli_connect($servername,$username,$password,$dbname);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

?>