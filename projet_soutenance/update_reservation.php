
<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? '';

// Correction ici : 'declined' → 'rejected'
if ($id && in_array($status, ['approved', 'rejected'])) {
    $stmt = $pdo->prepare('UPDATE reservations SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
}

header('Location: dashboard.php');
exit;
