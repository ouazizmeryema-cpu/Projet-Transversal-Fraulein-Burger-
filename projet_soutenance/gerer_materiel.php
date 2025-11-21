<?php
session_start();
require_once 'includes/db.php';

// Si on est en mode modification
$edit_mode = false;
$materiel_a_modifier = null;

if (isset($_GET['id'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare('SELECT * FROM equipment WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $materiel_a_modifier = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement de l'ajout
$success = null;
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['name'], $_POST['type'], $_POST['quantity'], $_POST['available_quantity']) &&
    !empty($_POST['description'])
) {
    $stmt = $pdo->prepare('INSERT INTO equipment (name, type, description, quantity, available_quantity) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $_POST['name'],
        $_POST['type'],
        $_POST['description'],
        (int)$_POST['quantity'],
        (int)$_POST['available_quantity']
    ]);
    $success = "MatÃ©riel ajoutÃ© avec succÃ¨s !";
    header('Location: gerer_materiel.php?success=1');
    exit;
}

if (isset($_GET['success'])) {
    $success = "MatÃ©riel ajoutÃ© avec succÃ¨s !";
}

// RÃ©cupÃ©rer la liste du matÃ©riel
$stmt = $pdo->query('SELECT * FROM equipment');
$materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GÃ©rer le MatÃ©riel</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/gerer_materiel.css">
  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
            <li><a href="dashboard.php">GÃ©rer les Demandes</a></li>
            <li><a href="voir_materiel.php">Voir le MatÃ©riel</a></li>
            <li><a href="gerer_materiel.php" class="active">GÃ©rer le MatÃ©riel</a></li>
        </ul>
        <div class="profile-badge">
            <span class="profile-circle">IT</span>
            <span class="profile-name">Personnel IT</span>
        </div>
    </nav>
    <main class="dashboard-main">
        <section class="dashboard-header">
            <h1>GÃ©rer le MatÃ©riel</h1>
            <p class="dashboard-subtitle">Ajouter, modifier ou supprimer du matÃ©riel informatique</p>
        </section>
        <section class="ajout-materiel-section">
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            <form class="ajout-materiel-form" method="post" action="">
                <h2><?php echo $edit_mode ? 'Modifier le matÃ©riel' : 'Ajouter un nouveau matÃ©riel'; ?></h2>

                <input type="hidden" name="id" value="<?php echo $materiel_a_modifier['id'] ?? ''; ?>">

                <div class="form-row">
                    <input type="text" name="name" placeholder="Nom du matÃ©riel" required value="<?php echo htmlspecialchars($materiel_a_modifier['name'] ?? ''); ?>">
                </div>

                <div class="form-row">
                    <input type="text" name="type" placeholder="CatÃ©gorie..." required value="<?php echo htmlspecialchars($materiel_a_modifier['type'] ?? ''); ?>">
                </div>

                <div class="form-row">
                    <input type="number" name="quantity" placeholder="QuantitÃ© totale" min="1" required value="<?php echo $materiel_a_modifier['quantity'] ?? ''; ?>">
                    <input type="number" name="available_quantity" placeholder="QuantitÃ© disponible" min="0" required value="<?php echo $materiel_a_modifier['available_quantity'] ?? ''; ?>">
                </div>

                <div class="form-row">
                    <button type="submit" class="btn-ajouter"><?php echo $edit_mode ? 'Mettre Ã  jour' : 'Ajouter'; ?></button>
                </div>
            </form>
        </section>
        <section class="liste-materiel-section">
            <h2>Inventaire actuel</h2>
            <table class="materiel-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>CatÃ©gorie</th>
                        <th>QuantitÃ© totale</th>
                        <th>QuantitÃ© dispo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($materiels as $m) : ?>
        <tr>
            <td><?php echo htmlspecialchars($m['name']); ?></td>
            <td><?php echo htmlspecialchars($m['type']); ?></td>
            <td><?php echo $m['quantity']; ?></td>
            <td><?php echo $m['available_quantity']; ?></td>
            <td>
                <button onclick="openModal('<?php echo $m['id']; ?>', '<?php echo htmlspecialchars($m['name']); ?>', '<?php echo htmlspecialchars($m['type']); ?>', '<?php echo $m['quantity']; ?>', '<?php echo $m['available_quantity']; ?>')">
                    Modifier
                </button>
                <a href="supprimer_materiel.php?id=<?php echo $m['id']; ?>" class="btn-supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce matÃ©riel ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</section>
    </main>

<!-- Modal -->
<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Modifier le matÃ©riel</h2>
        <form id="editForm">
            <input type="hidden" name="id" id="edit-id">
            <label>Nom :</label>
            <input type="text" name="name" id="edit-name" required><br>
            <label>Type :</label>
            <input type="text" name="type" id="edit-type" required><br>
            <label>QuantitÃ© :</label>
            <input type="number" name="quantity" id="edit-quantity" required><br>
            <label>QuantitÃ© dispo :</label>
            <input type="number" name="available_quantity" id="edit-available" required><br><br>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</div>

<script>
function openModal(id, name, type, quantity, available) {
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-type").value = type;
    document.getElementById("edit-quantity").value = quantity;
    document.getElementById("edit-available").value = available;
    document.getElementById("editModal").style.display = "block";
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

document.getElementById("editForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('modifier_materiel.php', {
        method: 'POST',
        body: formData
    }).then(res => res.text())
      .then(msg => {
        alert(msg);
        window.location.reload();
      });
});
</script>

<style>
.modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; }
.modal-content { background: white; padding: 20px; border-radius: 8px; min-width: 300px; }
.close-btn { float: right; cursor: pointer; font-size: 20px; }
</style>
</body>

        
</html> 