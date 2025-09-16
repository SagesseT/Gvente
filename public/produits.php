<?php
session_start();
 include("../config/conn.php"); 
 include("header.php");

$sql = "SELECT p.id_produit, p.nom_produit, c.nom_categorie, p.prix_achat, p.prix_vente, s.quantite_disponible, p.date_ajout
		FROM produits p
		LEFT JOIN categories c ON p.id_categorie = c.id_categorie
		LEFT JOIN stock s ON p.id_produit = s.id_produit";
$result = mysqli_query($conn, $sql);

?>

	<div class="container mt-5">
		<div class="d-flex justify-content-between align-items-center mb-4">
			<h2 class="mb-0">Liste des produits</h2>
			<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
			<a href="ajout_produit.php" class="btn btn-primary">
				<i class="bi bi-plus"></i> Ajouter un produit
			</a>
			<a href="ajout_categorie.php" class="btn btn-primary">
				<i class="bi bi-plus"></i> Ajouter Catégorie
			</a>
			<?php endif; ?>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Nom</th>
					<th>Catégorie</th>
					<th>Prix d'achat</th>
					<th>Prix de vente</th>
					<th>Quantité en stock</th>
					<th>Date d'ajout</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($result && mysqli_num_rows($result) > 0): ?>
					<?php while($row = mysqli_fetch_assoc($result)): ?>
						<tr>
							<td><?= htmlspecialchars($row['id_produit']) ?></td>
							<td><?= htmlspecialchars($row['nom_produit']) ?></td>
							<td><?= htmlspecialchars($row['nom_categorie']) ?></td>
							<td><?= htmlspecialchars($row['prix_achat']) ?> FC</td>
							<td><?= htmlspecialchars($row['prix_vente']) ?> FC</td>
							<td><?= htmlspecialchars($row['quantite_disponible']) ?></td>
							<td><?= htmlspecialchars($row['date_ajout']) ?></td>
						</tr>
					<?php endwhile; ?>
				<?php else: ?>
					<tr><td colspan="7">Aucun produit trouvé.</td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>

<?php include("footer.php"); ?>
