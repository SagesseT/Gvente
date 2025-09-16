<?php
session_start();
include "config/conn.php";

$error = '';
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM utilisateurs WHERE username='$username' AND mot_de_passe='$password'";
    $result = mysqli_query($conn, $sql);
    if ($user = mysqli_fetch_assoc($result)) {
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['id_role'];
        header('Location: public/index.php');
        exit();
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Gestion Ventes</title>
    <link href="vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="vendor/aos/aos.css" rel="stylesheet">
    <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fc; }
        .login-container { max-width: 400px; margin: 80px auto; }
    </style>
</head>
<body>
<div class="login-container card shadow p-4">
    <h2 class="mb-4 text-center">Connexion</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Se connecter</button>
    </form>
</div>
</body>
</html>
