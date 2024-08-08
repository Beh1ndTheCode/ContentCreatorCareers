<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require "../../include/config.inc.php";
    //require "../../include/dbms.inc.php";
    require "../../include/template2.inc.php";

    $main = new Template("frame");

    $body = new Template("about");

    //$result = $mysqli->query("SELECT * FROM content WHERE id = 1");
    //$data = $result->fetch_assoc();

    /* opzione 1 

    $body->setContent("title", $data['title']);
    $body->setContent("subtitle", $data['subtitle']);
    
    */
    /* opzione 2

    foreach($data as $k => $v) {
        $body->setContent($k,$v);
    }
    */

    $main->setContent("body", $body->get());

    $main->close();
?>