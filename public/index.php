<?php 
session_start();
include("../config/conn.php"); 
include("header.php");


$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$sql = "SELECT SUM(total_global) as total_jour FROM ventes WHERE DATE(date_vente) = '".mysqli_real_escape_string($conn, $date)."'";
$res = mysqli_query($conn, $sql);
$total_jour = 0;
if ($row = mysqli_fetch_assoc($res)) {
    $total_jour = $row['total_jour'] ? $row['total_jour'] : 0;
}
?>

<h1 class="text-center">Tableau de Bord</h1>

<div class="row justify-content-center mb-4">
  <div class="col-md-6">
    <form method="get" class="d-flex align-items-center gap-2">
      <label for="date" class="form-label mb-0"></label>
      <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>" max="<?= date('Y-m-d') ?>">
      <button type="submit" class="btn btn-primary">Voir</button>
    </form>
  </div>
</div>
<div class="row justify-content-center mb-4">
  <div class="col-md-6">
    <div class="alert alert-info text-center">
      <strong>Montant vendu le <?= date('d/m/Y', strtotime($date)) ?> :</strong> <?= number_format($total_jour, 2) ?> FC
    </div>
  </div>
</div>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
<div class="row mt-5">
  <div class="col-md-4">
    <div class="card text-bg-primary mb-3">
      <div class="card-body">
        <h5 class="card-title">Produits</h5>
        <p class="card-text">Gérer vos produits et catégories</p>
        <a href="produits.php" class="btn btn-light">Voir</a>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <div class="col-md-4">
    <div class="card text-bg-success mb-3">
      <div class="card-body">
        <h5 class="card-title">Ventes</h5>
        <p class="card-text">Gérer vos ventes</p>
        <a href="ventes.php" class="btn btn-light">Voir</a>
      </div>
    </div>
  </div>
  <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
  <div class="col-md-4">
    <div class="card text-bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title">Stock</h5>
        <p class="card-text">Suivi des mouvements</p>
        <a href="stock.php" class="btn btn-light">Voir</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php
$rupture = mysqli_query($conn, "SELECT p.nom_produit, c.nom_categorie FROM stock s 
    JOIN produits p ON s.id_produit = p.id_produit 
    JOIN categories c ON p.id_categorie = c.id_categorie 
    WHERE s.quantite_disponible = 0");
?>


<?php include("footer.php"); ?>
