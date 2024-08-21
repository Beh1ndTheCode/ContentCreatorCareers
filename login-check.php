<?php

require "include/config.inc.php";
require "include/dbms.inc.php";

# $passwd = md5("{$_POST['password']}");

# $result = $mysqli->query("SELECT username, email FROM `user` WHERE username = '{$_POST['username']}' AND password = '{$passwd}'");

$result = $mysqli->query("SELECT username, email FROM `user` WHERE username = '{$_POST['username']}' AND password = '{$_POST['password']}'");


if ($result->num_rows == 1) {
    $result = 1;
} else {
    $result = 0;
}


echo json_encode($result);


