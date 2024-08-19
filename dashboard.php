<?php

    session_start();

    require "include/config.inc.php";
    require "include/dbms.inc.php";
    require "include/template2.inc.php";
    require "include/auth.inc.php";


    $main = new Template("frame");
    $body = new Template("homepage");

    $main->setContent("body", $body->get());
    $main->close();

?>