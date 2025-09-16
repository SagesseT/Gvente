<?php
session_start();
include "../config/conn.php";
include("header.php");
// Vérifier l'ID de la vente
$id_vente = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_vente <= 0) {
    echo "<div class='alert alert-danger'>Facture introuvable.</div>";
    exit;
}

// Récupérer la vente
$vente = mysqli_query($conn, "SELECT v.*, u.nom, u.prenom, u.username 
    FROM ventes v 
    JOIN utilisateurs u ON v.id_utilisateur = u.id_utilisateur 
    WHERE v.id_vente = $id_vente");
$vente_data = mysqli_fetch_assoc($vente);
if (!$vente_data) {
    echo "<div class='alert alert-danger'>Facture introuvable.</div>";
    exit;
}

// Récupérer les détails
$details = mysqli_query($conn, "SELECT vd.*, p.nom_produit 
    FROM ventes_details vd 
    JOIN produits p ON vd.id_produit = p.id_produit 
    WHERE vd.id_vente = $id_vente");
?>

<style>
        @media print {
            .no-print { display: none; }
            body { background: #fff; }
            .facture { box-shadow: none; border: none; }
        }
        .facture { max-width: 600px; margin: 40px auto; padding: 30px; border: 1px solid #ccc; background: #fff; }
        .facture-header { text-align: center; margin-bottom: 30px; }
        .facture-footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
<div class="facture">
    <div class="facture-header">
        <h1>Boutique Kendal filS</h1>
        <h2>Facture de Vente</h2>
    </div>
    <div class="">
        <div><strong>   . Nom du (de la) vendeur(se) :</strong> <?= htmlspecialchars($vente_data['username']) ?></div>
        <div><strong>   . Numéro du Ticket :</strong> <?= $id_vente ?></div>
        <div><strong>   . Date/Heure :</strong> <?= date('d/m/Y H:i', strtotime($vente_data['date_vente'])) ?></div>
        
    </div>
    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix de Vente</th>
                <th>Qté</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; while ($d = mysqli_fetch_assoc($details)): ?>
            <tr>
                <td><?= htmlspecialchars($d['nom_produit']) ?></td>
                <td><?= number_format($d['prix_unitaire'], 2) ?> FC</td>
                <td><?= $d['quantite'] ?></td>
                <td><?= number_format($d['prix_unitaire'] * $d['quantite'], 2) ?> FC</td>
            </tr>
            <?php $total += $d['prix_unitaire'] * $d['quantite']; endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th><?= number_format($total, 2) ?> FC</th>
            </tr>
        </tfoot>
    </table>
    <div class="facture-footer text-center">
        <button class="btn btn-primary no-print" onclick="window.print()"><i class="bi bi-printer"></i></button>
        <div class="mt-4 text-muted" style="font-size: 0.95em;">
            Adresse : 123 Avenue de la Liberté, Quartier Central, Ville, Pays<br>
            Téléphone : +243 800 000 000 &nbsp;|&nbsp; Email : contact@gestionv.local
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
