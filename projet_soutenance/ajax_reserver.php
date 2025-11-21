<?php
header('Content-Type: application/json');
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $equipment_id = isset($_POST['equipment_id']) ? (int)$_POST['equipment_id'] : null;
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;
    $desc = $_POST['description'] ?? '';

    if ($user_id && $equipment_id && $date_debut && $date_fin) {
        $stmt = $pdo->prepare('
            INSERT INTO reservations (user_id, equipment_id, status, date_debut, date_fin, description, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ');
        $stmt->execute([
            $user_id,
            $equipment_id,
            'pending',
            $date_debut,
            $date_fin,
            $desc
        ]);
        echo json_encode(['success' => true, 'message' => 'Votre demande de réservation a bien été envoyée !']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
