<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? '';
$response = ['success' => false];

if ($id && in_array($status, ['approved', 'rejected'])) {
    $stmt = $pdo->prepare('UPDATE reservations SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
    $response['success'] = true;

    // Statistiques mises à jour
    $approved_today = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'approved' AND DATE(created_at) = CURDATE()")->fetchColumn();
    $rejected = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'rejected'")->fetchColumn();
    $pending = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetchColumn();

    $response['approved_today'] = (int)$approved_today;
    $response['rejected'] = (int)$rejected;
    $response['pending'] = (int)$pending;
}

echo json_encode($response);
