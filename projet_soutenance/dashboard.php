<?php
require_once 'includes/db.php';


$total_equipment = $pdo->query('SELECT COUNT(*) FROM equipment')->fetchColumn();
$pending_requests = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetchColumn();
$approved_today = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'approved' AND DATE(created_at) = CURDATE()")->fetchColumn();
$rejected = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'rejected'")->fetchColumn();

$stmt = $pdo->prepare('SELECT r.*, u.name as user_name, e.name as equipment_name FROM reservations r JOIN users u ON r.user_id = u.id JOIN equipment e ON r.equipment_id = e.id WHERE r.status = "pending" ORDER BY r.created_at DESC');
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getInitials($name) {
    $parts = explode(' ', $name);
    $ini = '';
    foreach ($parts as $p) {
        if ($p) $ini .= strtoupper($p[0]);
    }
    return substr($ini, 0, 2);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord IT</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="top-nav">
        <a href="index.php" class="back-btn" title="Retour">
            <!-- Back arrow SVG -->
            <svg width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 21l-6-7 6-7" stroke="#222" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <span class="nav-title">
            <span class="gear-icon">
                <!-- Gear SVG -->
                <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#e0f2fe"/><path d="M12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.07 2.93-1.42-1.42a1 1 0 0 0-1.42 0l-.71.71a7.007 7.007 0 0 0-2.12-.88V7a1 1 0 0 0-2 0v1.34a7.007 7.007 0 0 0-2.12.88l-.71-.71a1 1 0 0 0-1.42 0l-1.42 1.42a1 1 0 0 0 0 1.42l.71.71a7.007 7.007 0 0 0-.88 2.12H7a1 1 0 0 0 0 2h1.34a7.007 7.007 0 0 0 .88 2.12l-.71.71a1 1 0 0 0 0 1.42l1.42 1.42a1 1 0 0 0 1.42 0l.71-.71a7.007 7.007 0 0 0 2.12.88V21a1 1 0 0 0 2 0v-1.34a7.007 7.007 0 0 0 2.12-.88l.71.71a1 1 0 0 0 1.42 0l1.42-1.42a1 1 0 0 0 0-1.42l-.71-.71a7.007 7.007 0 0 0 .88-2.12H21a1 1 0 0 0 0-2h-1.34a7.007 7.007 0 0 0-.88-2.12l.71-.71a1 1 0 0 0 0-1.42Z" fill="#0ea5e9"/></svg>
            </span>
            Gestion IT
        </span>
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="active">Gérer les Demandes</a></li>
            <li><a href="voir_materiel.php">Voir le Matériel</a></li>
            <li><a href="gerer_materiel.php">Gérer le Matériel</a></li>
        </ul>
        <div class="profile-badge">
            <span class="profile-circle">IT</span>
            <span class="profile-name">Personnel IT</span>
        </div>
        <form action="logout.php" method="post" style="margin-left:auto;">
            <button type="submit" class="logout-btn" title="Déconnexion">Déconnexion</button>
        </form>
    </nav>
    <main class="dashboard-main">
        <section class="dashboard-header">
            <h1>Tableau de Bord IT</h1>
            <p class="dashboard-subtitle">Gérer les demandes de réservation et surveiller l'utilisation du système</p>
        </section>
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <!-- Cube SVG -->
                    <svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="32" height="32" rx="8" fill="#e0e7ff"/><path d="M16 8l8 4.5v7L16 24l-8-4.5v-7L16 8Zm0 0v7m0-7l8 4.5m-8-4.5L8 12.5m8 7v4.5m0-4.5l8-4.5m-8 4.5l-8-4.5" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Matériel Total</div>
                    <div class="stat-value"><?= $total_equipment ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow">
                    <!-- Clock SVG -->
                    <svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="32" height="32" rx="8" fill="#fef9c3"/><path d="M16 10v6l4 2" stroke="#eab308" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="16" cy="16" r="8" stroke="#eab308" stroke-width="2"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Demandes en Attente</div>
                    <div class="stat-value"><?= $pending_requests ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <!-- Check SVG -->
                    <svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="32" height="32" rx="8" fill="#d1fae5"/><path d="M10 17l5 5 7-9" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Approuvés Aujourd'hui</div>
                    <div class="stat-value"><?= $approved_today ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red">
                    <!-- Cross SVG -->
                    <svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="32" height="32" rx="8" fill="#fee2e2"/><path d="M12 12l8 8m0-8l-8 8" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-label">Rejetés</div>
                    <div class="stat-value"><?= $rejected ?></div>
                </div>
            </div>
        </section>
        <section class="requests-section">
            <h2>Demandes de Réservation</h2>
            <button id="refresh-demandes" class="btn btn-primary" style="margin-bottom:1em;">Rafraîchir les demandes</button>
            <div id="demandes-container">
                <?php if (empty($requests)): ?>
                    <p class="no-requests">Aucune demande en attente.</p>
                <?php else: ?>
                    <div class="requests-list">
                        <?php foreach ($requests as $req): ?>
                            <div class="request-card">
                                <div class="request-header">
                                    <span class="user-badge"><?= getInitials($req['user_name']) ?></span>
                                    <span class="user-name"><?= htmlspecialchars($req['user_name']) ?></span>
                                    <span class="status-badge">En Attente</span>
                                </div>
                                <div class="request-body">
                                    <div class="equipment-name">Matériel : <strong><?= htmlspecialchars($req['equipment_name']) ?></strong></div>
                                    <div class="duration-badge">Durée : <?= date('d M Y', strtotime($req['date_debut'])) ?> - <?= date('d M Y', strtotime($req['date_fin'])) ?></div>
                                    <div class="objective"><strong>Objectif :</strong> <?= !empty($req['description']) ? htmlspecialchars($req['description']) : 'Non spécifié' ?></div>
                                </div>
                                <div class="request-actions">
                                    <button class="approve-btn" data-id="<?= $req['id'] ?>" data-status="approved"><span class="btn-icon">✔️</span> Approuver</button>
                                    <button class="reject-btn" data-id="<?= $req['id'] ?>" data-status="rejected"><span class="btn-icon">✖️</span> Rejeter</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateStatCard(stat, value) {
            if (stat === 'approved_today') {
                document.querySelector('.stat-card:nth-child(3) .stat-value').textContent = value;
            } else if (stat === 'rejected') {
                document.querySelector('.stat-card:nth-child(4) .stat-value').textContent = value;
            } else if (stat === 'pending') {
                document.querySelector('.stat-card:nth-child(2) .stat-value').textContent = value;
            }
        }
        document.querySelectorAll('.approve-btn, .reject-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var card = btn.closest('.request-card');
                var id = btn.getAttribute('data-id');
                var status = btn.getAttribute('data-status');
                fetch('ajax_update_reservation.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + encodeURIComponent(id) + '&status=' + encodeURIComponent(status)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        card.remove();
                        updateStatCard('approved_today', data.approved_today);
                        updateStatCard('rejected', data.rejected);
                        updateStatCard('pending', data.pending);
                    }
                });
            });
        });
    });

    document.getElementById('refresh-demandes').onclick = async function() {
        const container = document.getElementById('demandes-container');
        container.innerHTML = '<div style="text-align:center;padding:1em;">Chargement...</div>';
        const resp = await fetch('ajax_get_reservations.php');
        const html = await resp.text();
        container.innerHTML = html;
    }
    </script>
</body>
</html> 