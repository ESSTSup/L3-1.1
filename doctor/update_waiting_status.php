<?php
require_once "../config/db_config.php";

$pdo = getPDOConnection();

$id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE waiting_list SET status = ? WHERE waiting_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$status, $id]);
