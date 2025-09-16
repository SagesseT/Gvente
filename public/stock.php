<?php
session_start();
 include("../config/conn.php"); 
 include("header.php");

$sql = "SELECT s.id_stock, p.nom_produit, c.nom_categorie, s.quantite_disponible, s.derniere_mise_a_jour
        FROM stock s
        LEFT JOIN produits p ON s.id_produit = p.id_produit
        LEFT JOIN categories c ON p.id_categorie = c.id_categorie";
$result = mysqli_query($conn, $sql);

?>

    <div class="container mt-5">
        <h2 class="mb-4">Stock des produits</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Stock</th>
                    <th>Nom du produit</th>
                    <th>Catégorie</th>
                    <th>Quantité disponible</th>
                    <th>Dernière mise à jour</th>
                </tr>
            </thead>
            <tbody>
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <?php
                // Avertissement si quantité > 10
                $row_class = ($row['quantite_disponible'] < 10) ? 'table-danger fw-bold' : '';
            ?>
            <tr class="<?= $row_class ?>">
                <td><?= htmlspecialchars($row['id_stock']) ?></td>
                <td><?= htmlspecialchars($row['nom_produit']) ?></td>
                <td><?= htmlspecialchars($row['nom_categorie']) ?></td>
                <td><?= htmlspecialchars($row['quantite_disponible']) ?></td>
                <td><?= htmlspecialchars($row['derniere_mise_a_jour']) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5">Aucun stock trouvé.</td></tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>
<?php include("footer.php"); ?>

