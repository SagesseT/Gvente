<?php
session_start();
include("../config/conn.php"); 
include("header.php");

// Création d'une nouvelle vente (facture)
if (isset($_POST['valider_vente'])) {
    $id_utilisateur = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
    if (!$id_utilisateur) {
        $message = "Erreur : aucun vendeur connecté.";
    } else {
        mysqli_query($conn, "INSERT INTO ventes (id_utilisateur, total_global) VALUES ('$id_utilisateur', 0)");
        $id_vente = mysqli_insert_id($conn);

        $total_global = 0;

        // Insérer les produits sélectionnés
        foreach ($_POST['produit'] as $index => $id_produit) {
            $quantite = $_POST['quantite'][$index];
            if ($quantite > 0) {
                $res = mysqli_query($conn, "SELECT prix_vente FROM produits WHERE id_produit=$id_produit");
                $prod = mysqli_fetch_assoc($res);
                $prix_unitaire = $prod['prix_vente'];
                $total = $prix_unitaire * $quantite;

                mysqli_query($conn, "INSERT INTO ventes_details (id_vente, id_produit, quantite, prix_unitaire) 
                                    VALUES ('$id_vente','$id_produit','$quantite','$prix_unitaire')");

                mysqli_query($conn, "UPDATE stock SET quantite_disponible = quantite_disponible - $quantite WHERE id_produit=$id_produit");

                $total_global += $total;
            }
        }

        mysqli_query($conn, "UPDATE ventes SET total_global='$total_global' WHERE id_vente='$id_vente'");

        // Redirection vers la page d'impression personnalisée
        header("Location: facture_imprime.php?id=$id_vente");
        exit();
    }
}

// Charger les produits disponibles
$produits = mysqli_query($conn, "SELECT * FROM produits");
$produits_array = [];
while($row = mysqli_fetch_assoc($produits)) {
    $produits_array[] = $row;
}
$produits = $produits_array;
?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Nouvelle Vente</h1>

    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <?php if (empty($message)): ?>
    <form method="post" class="card p-4 shadow vente-imprimable" id="vente-form">
        <div class="row mb-3">
            <div class="col-6">
                <h4>Facture de vente</h4>
                <div><strong>Date :</strong> <span id="date-vente"><?= date('d/m/Y H:i') ?></span></div>
                <div><strong>Vendeur :</strong> <?php if (isset($_SESSION['username'])) { echo htmlspecialchars($_SESSION['username']); } else { echo '<span class="text-danger">Non connecté</span>'; } ?></div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Produit</th>
                    <th>Prix Vente</th>
                    <th>Quantité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="products-table-body">
                <!-- Ligne initiale -->
            </tbody>
        </table>
        <button id="add-product-row" class="btn btn-primary mb-3 no-print" type="button"><i class="bi bi-plus"></i> Ajouter un produit</button>
        <div class="mb-3">
            <strong>Total : <span id="total-vente">0.00 FC</span></strong>
        </div>
        <div class="text-center">
            <button type="submit" name="valider_vente" class="btn btn-success">Valider la Vente & Imprimer</button>
        </div>
    </form>
    <?php endif; ?>

</div>
<script>
window.produitsData = <?= json_encode($produits) ?>;
</script>
<script src="../js/ventes.js"></script>
<?php include("footer.php"); ?>

