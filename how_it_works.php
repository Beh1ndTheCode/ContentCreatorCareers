<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require "include/config.inc.php";
    require "include/dbms.inc.php";
    require "include/template2.inc.php";

    $main = new Template("frame");
    $body = new Template("how_it_works");

    $def_img = "skins/jobhunt/images/white.jpg";

    $result = $mysqli->query("
        SELECT content.*, service.name
        FROM content
        JOIN service ON service.id = content.service_id
        WHERE service.id = 25
    ");
    $data = $result->fetch_assoc();
    $body->setContent("name",$data['name']);
    $body->setContent("sottotitolo1",$data['sottotitolo1']);
    $body->setContent("sottotitolo2",$data['sottotitolo2']);
    $body->setContent("sottotitolo3",$data['sottotitolo3']);
    $body->setContent("testo1",$data['testo1']);
    $body->setContent("testo2",$data['testo2']);
    $body->setContent("testo3",$data['testo3']);
    $img1 = $data['immagine1']!='' ? $data['immagine1'] : $def_img;
    $img2 = $data['immagine2']!='' ? $data['immagine2'] : $def_img;
    $img3 = $data['immagine3']!='' ? $data['immagine3'] : $def_img;
    $body->setContent("immagine1",$img1);
    $body->setContent("immagine2",$img2);
    $body->setContent("immagine3",$img3);


    $main->setContent("body", $body->get());

    $main->close();

?>