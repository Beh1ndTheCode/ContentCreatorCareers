<?php

$mysqli = new mysqli(
    $config['dbms'][$_SERVER['HTTP_HOST']]['host'], 
    $config['dbms'][$_SERVER['HTTP_HOST']]['user'],
    $config['dbms'][$_SERVER['HTTP_HOST']]['passwd'],
    $config['dbms'][$_SERVER['HTTP_HOST']]['dbname']);

if (!$mysqli) {
    die("Error");
}
