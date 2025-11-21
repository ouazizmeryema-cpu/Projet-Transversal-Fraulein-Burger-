<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $quantity = (int)$_POST['quantity'];
    $available_quantity = (int)$_POST['available_quantity'];

    $stmt = $pdo->prepare('UPDATE equipment SET name = ?, type = ?, quantity = ?, available_quantity = ? WHERE id = ?');
    $stmt->execute([$name, $type, $quantity, $available_quantity, $id]);

    echo " Matériel modifié avec succès.";
}