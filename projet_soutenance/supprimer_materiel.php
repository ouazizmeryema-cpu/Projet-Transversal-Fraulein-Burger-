<?php
session_start();
require_once 'includes/db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Supprimer le matériel de la base
    $stmt = $pdo->prepare('DELETE FROM equipment WHERE id = ?');
    $stmt->execute([$id]);

    // Redirection avec message
    header('Location: gerer_materiel.php?success=1');
    exit;
}
?>
