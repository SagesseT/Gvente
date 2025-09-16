<?php
session_start();
include "../config/conn.php";
include("header.php");
$id_vente = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Infos vente et vendeur
$sql = "SELECT v.id_vente, v.date_vente, v.total_global, u.nom, u.postnom, u.prenom
        FROM ventes v
        LEFT JOIN utilisateurs u ON v.id_utilisateur = u.id_utilisateur
        WHERE v.id_vente = $id_vente";
$vente = mysqli_fetch_assoc(mysqli_query($conn, $sql));

// Détails produits
$sql2 = "SELECT d.quantite, d.prix_unitaire, p.nom_produit
         FROM ventes_details d
         LEFT JOIN produits p ON d.id_produit = p.id_produit
         WHERE d.id_vente = $id_vente";
$details = mysqli_query($conn, $sql2);
?>

<div class="container mt-5">
    <h2 class="mb-4">Détails de la vente #<?= htmlspecialchars($vente['id_vente']) ?></h2>
    <p><strong>Date :</strong> <?= htmlspecialchars($vente['date_vente']) ?></p>
    <p><strong>Vendeur :</strong> <?= htmlspecialchars($vente['nom'].' '.$vente['postnom'].' '.$vente['prenom']) ?></p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; while($row = mysqli_fetch_assoc($details)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nom_produit']) ?></td>
                    <td><?= htmlspecialchars($row['quantite']) ?></td>
                    <td><?= number_format($row['prix_unitaire'], 2) ?> FC</td>
                    <td><?= number_format($row['prix_unitaire'] * $row['quantite'], 2) ?> FC</td>
                </tr>
                <?php $total += $row['prix_unitaire'] * $row['quantite']; ?>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th><?= number_format($total, 2) ?> FC</th>
            </tr>
        </tfoot>
    </table>
    <a href="vendu.php" class="btn btn-secondary">Retour à la liste</a>
</div>
<?php include("footer.php"); ?>
