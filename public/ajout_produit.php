<?php
session_start();
include "../config/conn.php";
include("header.php");
// Traitement ajout produit
$message = '';
if (isset($_POST['ajouter_produit'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom_produit']);
    $categorie = intval($_POST['id_categorie']);
    $prix_achat = floatval($_POST['prix_achat']);
    $prix_vente = floatval($_POST['prix_vente']);
    $sql = "INSERT INTO produits (nom_produit, id_categorie, prix_achat, prix_vente) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sidd', $nom, $categorie, $prix_achat, $prix_vente);
    if (mysqli_stmt_execute($stmt)) {
        $id_produit = mysqli_insert_id($conn);
        // Créer le stock initial à 0
        mysqli_query($conn, "INSERT INTO stock (id_produit, quantite_disponible) VALUES ($id_produit, 0)");
        $message = "Produit ajouté avec succès.";
    } else {
        $message = "Erreur lors de l'ajout du produit.";
    }
}
// Charger les catégories
$categories = mysqli_query($conn, "SELECT id_categorie, nom_categorie FROM categories");
?>

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Ajouter un produit</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="post" class="card p-3 shadow">
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Nom du produit</label>
                <input type="text" name="nom_produit" class="form-control form-control-sm" required>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label fw-semibold">Catégorie</label>
                <select name="id_categorie" class="form-select form-select-sm shadow-sm" required>
                    <option value="" disabled selected>Sélectionner une catégorie</option>
                    <?php mysqli_data_seek($categories, 0); while($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?= $cat['id_categorie'] ?>">
                            <?= htmlspecialchars($cat['nom_categorie']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-md-6">
                <label class="form-label">Prix d'achat</label>
                <input type="number" name="prix_achat" class="form-control form-control-sm" min="0" step="0.01" required>
            </div>
            <div class="mb-3 col-md-6">
                <label class="form-label">Prix de vente</label>
                <input type="number" name="prix_vente" class="form-control form-control-sm" min="0" step="0.01" required>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" name="ajouter_produit" class="btn btn-primary btn-sm">Ajouter</button>
            <a href="produits.php" class="btn btn-secondary btn-sm">Retour à la liste</a>
        </div>
    </form>
</div>

<?php include("footer.php"); ?>

