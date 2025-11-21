<?php
require_once 'includes/db.php';

$stmt = $pdo->query("
    SELECT r.*, u.name AS user_nom, e.name AS equip_nom
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN equipment e ON r.equipment_id = e.id
    ORDER BY r.created_at DESC
");

$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($reservations as $res) :
?>
<div class="request-card">
    <div class="request-info">
        <div><strong>Utilisateur :</strong> <?= htmlspecialchars($res['user_nom']) ?></div>
        <div><strong>Matériel :</strong> <?= htmlspecialchars($res['equip_nom']) ?></div>
        <div><strong>Durée :</strong> <?= htmlspecialchars($res['date_debut']) ?> → <?= htmlspecialchars($res['date_fin']) ?></div>
        <div><strong>Description :</strong> <?= htmlspecialchars($res['description']) ?></div>
        <div><strong>Statut :</strong> 
            <span class="statut statut-<?= $res['status'] ?>">
                <?= ucfirst($res['status']) ?>
            </span>
        </div>
    </div>
    <div class="request-actions">
        <?php if ($res['status'] === 'pending') : ?>
            <button class="btn-accept" data-id="<?= $res['id'] ?>">Accepter</button>
            <button class="btn-reject" data-id="<?= $res['id'] ?>">Refuser</button>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
