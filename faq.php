<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require "include/config.inc.php";
    require "include/dbms.inc.php";
    require "include/template2.inc.php";

    $main = new Template("frame");
    $body = new Template("faq");

    $result = $mysqli->query("
        SELECT content.*, service.name
        FROM content
        JOIN service ON service.id = content.service_id
        WHERE service.id = 24
    ");
    $data = $result->fetch_assoc();
    $body->setContent("name",$data['name']);
    $body->setContent("sottotitolo1",$data['sottotitolo1']);
    $body->setContent("sottotitolo2",$data['sottotitolo2']);
    $body->setContent("sottotitolo3",$data['sottotitolo3']);
    $body->setContent("sottotitolo4",$data['sottotitolo4']);
    $body->setContent("testo1",$data['testo1']);
    $body->setContent("testo2",$data['testo2']);
    $body->setContent("testo3",$data['testo3']);
    $body->setContent("testo4",$data['testo4']);



    $main->setContent("body", $body->get());

    $main->close();

?>