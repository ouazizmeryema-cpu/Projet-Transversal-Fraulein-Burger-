<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Réservation de Matériel</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="role-select-container">
        <h1 class="main-title">Système de Réservation de Matériel</h1>
        <p class="subtitle">Sélectionnez votre rôle pour continuer</p>
        <div class="roles">
            <div class="role-card">
                <div class="role-icon blue">
                    <!-- Person Icon SVG -->
                    <svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="24" fill="#e0e7ff"/><path d="M24 27c4.418 0 8 2.686 8 6v2H16v-2c0-3.314 3.582-6 8-6Zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" fill="#2563eb"/></svg>
                </div>
                <h2>Enseignant</h2>
                <p>Parcourir et réserver le matériel disponible</p>
                <a href="login_enseignant.php" class="role-btn">Continuer en tant qu'Enseignant</a>
            </div>
            <div class="role-card">
                <div class="role-icon green">
                    <!-- Gear Icon SVG -->
                    <svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="24" fill="#d1fae5"/><path d="M24 17a7 7 0 1 1 0 14 7 7 0 0 1 0-14Zm0 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm9.9 3.1-2.1-2.1a1.5 1.5 0 0 0-2.1 0l-1.05 1.05a9.01 9.01 0 0 0-3.15-1.3V17a1.5 1.5 0 0 0-3 0v1.75a9.01 9.01 0 0 0-3.15 1.3l-1.05-1.05a1.5 1.5 0 0 0-2.1 0l-2.1 2.1a1.5 1.5 0 0 0 0 2.1l1.05 1.05a9.01 9.01 0 0 0-1.3 3.15H17a1.5 1.5 0 0 0 0 3h1.75a9.01 9.01 0 0 0 1.3 3.15l-1.05 1.05a1.5 1.5 0 0 0 0 2.1l2.1 2.1a1.5 1.5 0 0 0 2.1 0l1.05-1.05a9.01 9.01 0 0 0 3.15 1.3V31a1.5 1.5 0 0 0 3 0v-1.75a9.01 9.01 0 0 0 3.15-1.3l1.05 1.05a1.5 1.5 0 0 0 2.1 0l2.1-2.1a1.5 1.5 0 0 0 0-2.1l-1.05-1.05a9.01 9.01 0 0 0 1.3-3.15H31a1.5 1.5 0 0 0 0-3h-1.75a9.01 9.01 0 0 0-1.3-3.15l1.05-1.05a1.5 1.5 0 0 0 0-2.1Z" fill="#10b981"/></svg>
                </div>
                <h2>Personnel IT</h2>
                <p>Gérer le matériel et approuver les demandes</p>
                <a href="login.php?role=it_staff" class="role-btn">Continuer en tant que Personnel IT</a>
            </div>
        </div>
    </div>
</body>
</html> 