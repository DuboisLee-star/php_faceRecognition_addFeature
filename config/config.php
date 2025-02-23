<?php

session_start();

$host = "localhost"; /* Host name */
$user = "wwhost_hostmarq";              /* User */
$password = "?gOP?PHH}AwHH{{{P??OT0gG";        /* Password */
$dbname = "wwhost_hostmarq";            /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);

// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}