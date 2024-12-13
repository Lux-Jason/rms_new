<?php
include 'db_config.php';
include 'connect.php';

$dishId = $_POST['dishId'];
$newAmount = $_POST['newAmount'];

if (isset($dishId) && isset($newAmount)) {
    include 'pagination.php';
    updateAmount($dishId, $newAmount);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}