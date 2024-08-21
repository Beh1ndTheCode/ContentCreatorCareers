<?php

require "include/config.inc.php";
require "include/dbms.inc.php";

# $passwd = md5("{$_POST['password']}");

# $result = $mysqli->query("SELECT username, email FROM `user` WHERE username = '{$_POST['username']}' AND password = '{$passwd}'");

$result_username = $mysqli->query("SELECT username FROM `user` WHERE username = '{$_POST['username']}'");
$result_email = $mysqli->query("SELECT email FROM `user` WHERE email = '{$_POST['email']}'");

if ($result_username->num_rows != 0) {
    $result = 0;
} elseif($result_email->num_rows != 0) {
    $result = 1;
} else{
    $result = 2;
}

echo json_encode($result);
