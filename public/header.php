<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des ventes</title>
    <meta name="author" content="">
    <link href="../vendor/bootstrapc/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../vendor/aos/aos.css" rel="stylesheet">
    <link href="../vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="../vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Gestion Ventes</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="ventes.php">Ventes</a></li>
        <li class="nav-item"><a class="nav-link" href="produits.php">Produits</a></li>
        <li class="nav-item"><a class="nav-link" href="vendu.php">Vendue</a></li>

      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>

        <li class="nav-item"><a class="nav-link" href="stock.php">Stock</a></li>
        <li class="nav-item"><a class="nav-link" href="utilisateurs.php"><i class="bi bi-person"></i> Utilisateurs</a></li>
        <li class="nav-item"><a class="nav-link" href="entree.php">Entree</a></li>
        
      <?php endif; ?>

      </ul>
      </ul>
    </div>
    <div class="d-flex">
      <?php session_start(); ?>
      <?php if (isset($_SESSION['username'])): ?>
        <span class="navbar-text text-white me-3">
          Connecté en tant que: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
        </span> <br>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Quitter</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container mt-4">
  <?php
  // Vérifier si l'utilisateur est connecté
  
if (!isset($_SESSION["username"])) {
    header("Location: ../index.php");
    exit();
}
?>
