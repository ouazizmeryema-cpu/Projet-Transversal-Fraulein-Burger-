<?php
session_start();
require_once 'includes/db.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND role = "teacher"');
$stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        header('Location: enseignant_dashboard.php');
        exit;
    } else {
        $error = "Identifiants incorrects ou accès non autorisé.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-card">
        <div class="lock-icon">
            <!-- Lock SVG -->
            <svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="24" fill="#e5e7eb"/><path d="M32 22v-3a8 8 0 0 0-16 0v3a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V24a2 2 0 0 0-2-2Zm-12-3a4 4 0 1 1 8 0v3h-8v-3Zm12 15H16V24h16v10Z" fill="#111827"/></svg>
        </div>
        <h2 class="login-title">Connexion Enseignant</h2>
        <p class="login-subtitle">Accédez au système de réservation de matériel</p>
        <?php if ($error): ?>
            <div class="login-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="login_enseignant.php">
            <label for="username">Nom d'utilisateur</label>
            <div class="input-group">
                <span class="input-icon">
                    <!-- User SVG -->
                    <svg width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 2c-2.67 0-8 1.34-8 4v1h16v-1c0-2.66-5.33-4-8-4Z" fill="#a0aec0"/></svg>
                </span>
                <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required autocomplete="username">
            </div>
            <label for="password">Mot de passe</label>
            <div class="input-group">
                <span class="input-icon">
                    <!-- Lock SVG -->
                    <svg width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 1a4 4 0 0 0-4 4v2H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-1V5a4 4 0 0 0-4-4Zm-2 4a2 2 0 1 1 4 0v2H7V5Zm8 4v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1Z" fill="#a0aec0"/></svg>
                </span>
                <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required autocomplete="current-password">
                <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1">
                    <!-- Eye SVG -->
                    <svg id="eye" width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 3C4 3 1 9 1 9s3 6 8 6 8-6 8-6-3-6-8-6Zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4Zm0-6a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" fill="#a0aec0"/></svg>
                </button>
            </div>
            <button type="submit" class="login-btn">Se connecter</button>
        </form>
        
    </div>
    <script>
    function togglePassword() {
        var pwd = document.getElementById('password');
        var eye = document.getElementById('eye');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            eye.innerHTML = '<path d="M1 9s3-6 8-6 8 6 8 6-3 6-8 6-8-6-8-6Zm8 4c2.21 0 4-1.79 4-4 0-.34-.04-.67-.1-.99l2.13-2.13a.75.75 0 1 0-1.06-1.06l-2.13 2.13A3.98 3.98 0 0 0 9 5a4 4 0 0 0-4 4c0 2.21 1.79 4 4 4Z" fill="#a0aec0"/>';
        } else {
            pwd.type = 'password';
            eye.innerHTML = '<path d="M9 3C4 3 1 9 1 9s3 6 8 6 8-6 8-6-3-6-8-6Zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4Zm0-6a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" fill="#a0aec0"/>';
        }
    }
    </script>
</body>
</html> 