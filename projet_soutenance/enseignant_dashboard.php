<?php
session_start();
require_once 'includes/db.php';

$success = null;

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modal_reservation'])) {
    $user_id = $_POST['user_id'] ?? ($_SESSION['user_id'] ?? 1);
    $equipment_id = (int)$_POST['equipment_id'];
    $date_debut = $_POST['date_debut'] ?? date('Y-m-d');
    $date_fin = $_POST['date_fin'] ?? date('Y-m-d');
    $desc = $_POST['description'] ?? '';

    $stmt = $pdo->prepare('INSERT INTO reservations (user_id, equipment_id, status, date_debut, date_fin, description, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
    $stmt->execute([$user_id, $equipment_id, 'pending', $date_debut, $date_fin, $desc]);

    $success = "Votre demande de réservation a bien été envoyée !";
}

// Récupérer la liste du matériel
$stmt = $pdo->query('SELECT * FROM equipment');
$materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user_id = $_SESSION['user_id'] ?? null;
$reservations = [];
if ($user_id) {
    $stmt = $pdo->prepare("
        SELECT r.*, e.name AS equipment_name 
        FROM reservations r 
        JOIN equipment e ON r.equipment_id = e.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Enseignant</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/materiel.css">
    <link rel="stylesheet" href="css/reservation_modal.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
    function openModal(equipmentId, equipmentName) {
        document.getElementById('modal-overlay').style.display = 'flex';
        document.getElementById('modal-equipment-id').value = equipmentId;
        document.getElementById('modal-equipment-name').textContent = equipmentName;
        document.getElementById('modal-form').setAttribute('data-equip', equipmentId);
    }
    function closeModal() {
        document.getElementById('modal-overlay').style.display = 'none';
    }
    window.onload = function() {
        closeModal();
        document.getElementById('modal-form').onsubmit = async function(e) {
            e.preventDefault();
            const form = e.target;
            const data = new FormData(form);
            const equipId = form.getAttribute('data-equip');
            const btnRes = document.querySelector('.btn-reserver[data-equip="'+equipId+'"]');
            const resp = await fetch('ajax_reserver.php', { method: 'POST', body: data });
            const json = await resp.json();
            closeModal();
            if(json.success) {
                let msg = document.getElementById('success-message');
                if (!msg) {
                    msg = document.createElement('div');
                    msg.id = 'success-message';
                    msg.className = 'success-message';
                    document.querySelector('.dashboard-header').after(msg);
                }
                msg.innerText = json.message;
                if(btnRes) btnRes.style.display = 'none';
            } else {
                alert(json.message);
            }
        }
    }
    </script>
</head>
<body>
    <nav class="top-nav">
        <a href="index.php" class="back-btn" title="Retour">
            <svg width="28" height="28" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 21l-6-7 6-7" stroke="#222" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <span class="nav-title">
            <span class="gear-icon">
                <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="12" fill="#e0f2fe"/><path d="M12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.07 2.93-1.42-1.42a1 1 0 0 0-1.42 0l-.71.71a7.007 7.007 0 0 0-2.12-.88V7a1 1 0 0 0-2 0v1.34a7.007 7.007 0 0 0-2.12.88l-.71-.71a1 1 0 0 0-1.42 0l-1.42 1.42a1 1 0 0 0 0 1.42l.71.71a7.007 7.007 0 0 0-.88 2.12H7a1 1 0 0 0 0 2h1.34a7.007 7.007 0 0 0 .88 2.12l-.71.71a1 1 0 0 0 0 1.42l1.42 1.42a1 1 0 0 0 1.42 0l.71-.71a7.007 7.007 0 0 0 2.12.88V21a1 1 0 0 0 2 0v-1.34a7.007 7.007 0 0 0 2.12-.88l.71.71a1 1 0 0 0 1.42 0l1.42-1.42a1 1 0 0 0 0-1.42l-.71-.71a7.007 7.007 0 0 0 .88-2.12H21a1 1 0 0 0 0-2h-1.34a7.007 7.007 0 0 0-.88-2.12l.71-.71a1 1 0 0 0 0-1.42Z" fill="#0ea5e9"/></svg>
            </span>
            Gestion IT
        </span>
        <ul class="nav-menu">
            <li><a href="enseignant_dashboard.php" class="active">Voir le Matériel</a></li>
        </ul>
        <div class="profile-badge">
            <span class="profile-circle">EN</span>
            <span class="profile-name">Enseignant</span>
        </div>
        <form action="logout.php" method="post" style="margin-left:auto;">
            <button type="submit" class="logout-btn" title="Déconnexion">Déconnexion</button>
        </form>
    </nav>
    <main class="dashboard-main">
        <section class="dashboard-header">
            <h1>Matériel disponible</h1>
            <p class="dashboard-subtitle">Consultez le matériel et effectuez vos réservations</p>
        </section>
        <?php if ($success): ?>
            <div class="success-message" style="margin-bottom:2em;"> <?php echo $success; ?> </div>
        <?php endif; ?>
        <div class="materiel-list">
            <?php foreach ($materiels as $m):
                $statut = $m['available_quantity'] == 0 ? '❌ Indisponible' : '✅ Disponible';
                $statut_class = $m['available_quantity'] == 0 ? 'statut-pas-stock' : 'statut-disponible';
                $categorie = $m['type'] ?? 'Informatique';
                $stock_total = $m['quantity'];
            ?>
            <div class="materiel-card">
                <div class="materiel-header">
                    <span class="materiel-nom"><?php echo htmlspecialchars($m['name']); ?></span>
                </div>
                <div class="materiel-info">
                    <p class="materiel-categorie"><?php echo htmlspecialchars($categorie); ?></p>
                    <p class="materiel-quantite"><?php echo $m['available_quantity']; ?> sur <?php echo $stock_total; ?> disponibles</p>
                    <p class="statut <?php echo $statut_class; ?>"><?php echo $statut; ?></p>
                    <?php if ($statut === '✅ Disponible') : ?>
                        <button class="btn-reserver" data-equip="<?php echo $m['id']; ?>" onclick="openModal('<?php echo $m['id']; ?>', '<?php echo htmlspecialchars(addslashes($m['name'])); ?>')">Réserver</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <section class="mes-reservations">
            <h2>Mes Réservations</h2>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Matériel</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($reservations as $res): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($res['equipment_name']); ?></td>
                        <td><?php echo htmlspecialchars($res['date_debut']); ?></td>
                        <td><?php echo htmlspecialchars($res['date_fin']); ?></td>
                        <td class="<?php
    if ($res['status'] === 'approved') echo 'accepted';
    elseif ($res['status'] === 'rejected' || $res['status'] === 'declined') echo 'rejected';
    else echo 'pending';
?>">
    <?php
        if ($res['status'] === 'approved') echo "✅ Acceptée";
        elseif ($res['status'] === 'rejected' || $res['status'] === 'declined') echo "❌ Refusée";
        else echo "⏳ En attente";
    ?>
</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <!-- Modale de réservation -->
        <div class="modal-overlay" id="modal-overlay" style="display:none;">
            <div class="modal">
                <button class="modal-close" onclick="closeModal()" title="Fermer">&times;</button>
                <h2>Réserver : <span id="modal-equipment-name"></span></h2>
                <form class="modal-form" id="modal-form" method="post" action="">
                    <input type="hidden" name="modal_reservation" value="1">
                    <input type="hidden" name="equipment_id" id="modal-equipment-id">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                    <label>Date de début
                        <input type="date" name="date_debut" value="<?php echo date('Y-m-d'); ?>" required>
                    </label>
                    <label>Date de fin
                        <input type="date" name="date_fin" value="<?php echo date('Y-m-d'); ?>" required>
                    </label>
                    <label>Description
                        <textarea name="description" placeholder="Motif ou commentaire" required></textarea>
                    </label>
                    <button type="submit" class="btn-modal-envoyer">Envoyer</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
