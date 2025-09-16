<?php
session_start();
include "../config/conn.php";
include("header.php");

$message = '';
if (isset($_POST['ajouter_categorie'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom_categorie']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $sql = "INSERT INTO categories (nom_categorie, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $nom, $description);
    if (mysqli_stmt_execute($stmt)) {
        $message = "Catégorie ajoutée avec succès.";
    } else {
        $message = "Erreur lors de l'ajout de la catégorie.";
    }
}
?>

<div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4">Ajouter une catégorie</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="post" class="card p-3 shadow">
        <div class="mb-3">
            <label class="form-label">Nom de la catégorie</label>
            <input type="text" name="nom_categorie" class="form-control form-control-sm" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control form-control-sm" rows="2"></textarea>
        </div>
        <button type="submit" name="ajouter_categorie" class="btn btn-primary btn-sm">Ajouter</button>
        <a href="produits.php" class="btn btn-secondary btn-sm ms-2">Retour</a>
    </form>
</div>

<?php include("footer.php"); ?>
