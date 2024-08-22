<?php

require "include/config.inc.php";
require "include/dbms.inc.php";

$result = 0; 

echo json_encode($result);

//query per aggiungerel'user al database, con in input: username, password, email, e tipo di utente (candidate o employer)