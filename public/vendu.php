<?php
session_start();
include "../config/conn.php";
include("header.php");

// Suppression d'une vente
if (isset($_GET['delete']) && is_numeric($_GET['delete']) && isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    $id = intval($_GET['delete']);

    // Récupérer les détails de la vente pour remettre les quantités
    $details = mysqli_query($conn, "SELECT id_produit, quantite FROM ventes_details WHERE id_vente = $id");
    while ($d = mysqli_fetch_assoc($details)) {
        // Remettre la quantité dans le stock
        mysqli_query($conn, "UPDATE stock SET quantite_disponible = quantite_disponible + {$d['quantite']} WHERE id_produit = {$d['id_produit']}");
    }

    // Supprimer la vente
    mysqli_query($conn, "DELETE FROM ventes_details WHERE id_vente = $id");
    mysqli_query($conn, "DELETE FROM ventes WHERE id_vente = $id");
    header('Location: vendu.php');
    exit();
}

// Pagination et filtre par date
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;
$date_filter = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Par défaut, la date du jour

$where = '';
if ($date_filter) {
    $where = "WHERE DATE(v.date_vente) = '" . mysqli_real_escape_string($conn, $date_filter) . "'";
}

$sql_count = "SELECT COUNT(*) as total FROM ventes v $where";
$count_res = mysqli_query($conn, $sql_count);
$total_rows = mysqli_fetch_assoc($count_res)['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "SELECT v.id_vente, v.date_vente, v.total_global, u.nom, u.postnom, u.prenom
        FROM ventes v
        LEFT JOIN utilisateurs u ON v.id_utilisateur = u.id_utilisateur
        $where
        ORDER BY v.date_vente DESC
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);
?>

<div class="container mt-5">
    <h2 class="mb-4">Liste des ventes</h2>
    <form method="get" class="mb-3 d-flex gap-2 no-print">
        <input type="date" name="date" value="<?= htmlspecialchars($date_filter) ?>" class="form-control" style="max-width:200px;">
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="vendu.php" class="btn btn-secondary">Réinitialiser</a>
        <button type="button" class="btn btn-success" onclick="window.print()"><i class="bi bi-printer"></i> Imprimer</button>
    </form>
    <div id="print-section">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Vente</th>
                    <th>Date</th>
                    <th>Vendeur</th>
                    <th>Total</th>
                    <th class="no-print">Détails</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_vente']) ?></td>
                            <td><?= htmlspecialchars($row['date_vente']) ?></td>
                            <td><?= htmlspecialchars($row['nom'].' '.$row['postnom'].' '.$row['prenom']) ?></td>
                            <td><?= number_format($row['total_global'], 2) ?> FC</td>
                            <td class="no-print">
                                <a href="details_vente.php?id=<?= $row['id_vente'] ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a> 
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
                                <a href="vendu.php?delete=<?= $row['id_vente'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette vente ?');">
                                    <i class="bi bi-trash3"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">Aucune vente trouvée.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <nav class="no-print">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?><?= $date_filter ? '&date=' . $date_filter : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<style>
@media print {
    .no-print, .no-print * { display: none !important; }
    body { background: #fff; }
    #print-section { margin-top: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    th { background: #f2f2f2; }
}
</style>
<?php include("footer.php"); ?>