<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("content_menagment");

$result = $mysqli->query("
    SELECT 
        service_id,service.name
    FROM content
    JOIN service ON service.id = content.service_id
");
$pages = [];
while ($row = $result->fetch_assoc()){
    $pages[] = [
        'id' => $row['service_id'],
        'name' => $row['name']
    ];
}
$pages_html = '';
foreach ($pages as $page) {
    $id = $page['id'];
    $name = $page['name'];
    $pages_html .= "<option value='$id'>$name</option>";
}
$body->setContent("pages", $pages_html);

$main->setContent("body", $body->get());
$main->close();

?>