<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$id = $_POST['value'];

$stmt = $mysqli->prepare("
    SELECT content.*,service.name
    FROM content
    JOIN service ON service.id = content.service_id
    WHERE service.id = ?
");
$stmt->bind_param("s", $_POST['value']);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$mysqli->close();
?>