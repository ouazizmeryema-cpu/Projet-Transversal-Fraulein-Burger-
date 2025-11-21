<?php
session_start();
require_once 'includes/db.php'; 

// Vérifier si l'utilisateur est connecté et son rôle
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Récupérer la liste du matériel
$stmt = $pdo->query('SELECT * FROM equipment');
$materiels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul résumé
$total_articles = count($materiels);
$total_dispo = 0;
foreach ($materiels as $m) {
    $total_dispo += $m['available_quantity'];
}
// Fonction pour icône selon le type
function getIcon($name) {
    $name = strtolower($name);
    if (strpos($name, 'laptop') !== false || strpos($name, 'macbook') !== false || strpos($name, 'ordinateur') !== false) return '💻';
    if (strpos($name, 'ipad') !== false || strpos($name, 'tablet') !== false) return '📱';
    if (strpos($name, 'camera') !== false || strpos($name, 'caméra') !== false || strpos($name, 'webcam') !== false) return '📷';
    if (strpos($name, 'photo') !== false || strpos($name, 'booth') !== false || strpos($name, 'image') !== false) return '📸';
    if (strpos($name, 'microphone') !== false) return '🎤';
    if (strpos($name, 'projector') !== false || strpos($name, 'projecteur') !== false) return '📽️';
    if (strpos($name, 'drone') !== false) return '🛸';
    if (strpos($name, 'headphone') !== false || strpos($name, 'casque') !== false) return '🎧';
    if (strpos($name, 'souris') !== false) return '🖱️';
    if (strpos($name, 'clavier') !== false) return '⌨️';
    return '🔧';
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire du Matériel</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/materiel.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script>
    // Toast + scroll
    function showToastAndScroll() {
        const toast = document.getElementById('toast');
        toast.classList.add('show');
        setTimeout(() => { toast.classList.remove('show'); }, 2000);
        document.getElementById('catalogue-materiel').scrollIntoView({behavior: 'smooth'});
    }
    // Fonction pour scroller vers la section catalogue si scroll=1 dans l'URL
    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('scroll') === '1') {
            const section = document.getElementById('catalogue-materiel');
            if (section) {
                section.scrollIntoView({behavior: 'smooth'});
            }
        }
    }
    </script>
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
            <li><a href="dashboard.php">Gérer les Demandes</a></li>
            <li><a href="voir_materiel.php" class="active">Voir le Matériel</a></li>
            <li><a href="gerer_materiel.php">Gérer le Matériel</a></li>
        </ul>
        <div class="profile-badge">
            <span class="profile-circle">IT</span>
            <span class="profile-name">Personnel IT</span>
        </div>
    </nav>
    <main class="dashboard-main">
        <section class="dashboard-header">
            <h1>Inventaire du Matériel</h1>
            <p class="dashboard-subtitle">Consulter la liste complète du matériel et son état</p>
        </section>
        
        <div id="catalogue-materiel" class="materiel-list">
            <?php foreach ($materiels as $m) :
                // Déterminer le statut
                if ($m['available_quantity'] == 0) {
                    $statut = '❌ Indisponible';
                    $statut_class = 'statut-pas-stock';
                } else {
                    $statut = '✅ Disponible';
                    $statut_class = 'statut-disponible';
                }
                // Catégorie (si colonne non existante, valeur par défaut)
                $categorie = $m['type'] ?? 'Informatique';
                // Stock total (si colonne non existante, on prend le stock actuel)
                $stock_total = $m['quantity'];
                $icon = getIcon($m['name']);
            ?>
            <div class="materiel-card">
                <div class="materiel-header">
                    <span class="materiel-icone"><?php echo $icon; ?></span>
                    <span class="materiel-nom"><?php echo htmlspecialchars($m['name']); ?></span>
                </div>
                
                <div class="materiel-info">
                    <h2><?php echo htmlspecialchars($m['name']); ?></h2>
                    
                    <p class="materiel-quantite"><?php echo $m['available_quantity']; ?> sur <?php echo $stock_total; ?> disponibles</p>
                    <p class="statut <?php echo $statut_class; ?>"><?php echo $statut; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html> 