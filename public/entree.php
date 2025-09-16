<?php
session_start();
include "../config/conn.php";
 include("header.php");
// Gestion de l'ajout d'entrée
$message = '';
if (isset($_POST['ajouter_entree'])) {
    $id_produit = intval($_POST['id_produit']);
    $quantite = intval($_POST['quantite']);
    $prix_unitaire = floatval($_POST['prix_unitaire']);
    $id_utilisateur = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1; // Par défaut 1 si non connecté

    $sql = "INSERT INTO entrees (id_produit, quantite, prix_unitaire, id_utilisateur) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iidi', $id_produit, $quantite, $prix_unitaire, $id_utilisateur);
    if (mysqli_stmt_execute($stmt)) {
        // Mise à jour du stock
        mysqli_query($conn, "UPDATE stock SET quantite_disponible = quantite_disponible + $quantite WHERE id_produit = $id_produit");
        $message = "Entrée ajoutée avec succès.";
    } else {
        $message = "Erreur lors de l'ajout.";
    }
}
// Charger les produits
$produits = mysqli_query($conn, "SELECT id_produit, nom_produit FROM produits");
?>

<div class="container mt-5">
    <h2 class="mb-4">Ajouter une entrée de produit</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <form method="post" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Produit</label>
            <select name="id_produit" class="form-select" required>
                <option value="">Sélectionner un produit</option>
                <?php while($p = mysqli_fetch_assoc($produits)): ?>
                    <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom_produit']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantité</label>
            <input type="number" name="quantite" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prix unitaire</label>
            <input type="number" name="prix_unitaire" class="form-control" min="0" step="0.01" required>
        </div>
        <button type="submit" name="ajouter_entree" class="btn btn-primary">Ajouter l'entrée</button>
    </form>
</div>

<?php include("footer.php"); ?>

