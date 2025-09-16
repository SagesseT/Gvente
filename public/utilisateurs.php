<?php
session_start();
include("../config/conn.php");
include("header.php");

// Vérifier que seul l'administrateur peut accéder
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

$message = '';
// Ajout d'un utilisateur
if (isset($_POST['ajouter'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = intval($_POST['role']);

    // Vérifier si le username existe déjà
    $check = mysqli_query($conn, "SELECT id_utilisateur FROM utilisateurs WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $message = "<div class='alert alert-danger'>Nom d'utilisateur déjà utilisé.</div>";
    } else {
        $ok = mysqli_query($conn, "INSERT INTO utilisateurs (nom, prenom, username, password, id_role) VALUES ('$nom', '$prenom', '$username', '$password', '$role')");
        if ($ok) {
            $message = "<div class='alert alert-success'>Utilisateur ajouté avec succès.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de l'ajout.</div>";
        }
    }
}

// Modification du rôle
if (isset($_POST['modifier_role'])) {
    $id = intval($_POST['id_utilisateur']);
    $role = intval($_POST['role']);
    mysqli_query($conn, "UPDATE utilisateurs SET id_role='$role' WHERE id_utilisateur='$id'");
}

// Liste des utilisateurs
$res = mysqli_query($conn, "SELECT id_utilisateur, nom, prenom, username, id_role FROM utilisateurs");
?>

<h2 class="mb-4">Gestion des utilisateurs</h2>
<?= $message ?>

<!-- Formulaire d'ajout -->
<form method="post" class="mb-4 card p-3">
    <h5>Ajouter un utilisateur</h5>
    <div class="row g-2">
        <div class="col"><input type="text" name="nom" class="form-control" placeholder="Nom" required></div>
        <div class="col"><input type="text" name="prenom" class="form-control" placeholder="Prénom" required></div>
        <div class="col"><input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required></div>
        <div class="col"><input type="password" name="password" class="form-control" placeholder="Mot de passe" required></div>
        <div class="col">
            <select name="role" class="form-select" required>
                <option value="1">Administrateur</option>
                <option value="2">Caissier</option>
                <option value="3">Vendeur</option>
            </select>
        </div>
        <div class="col">
            <button type="submit" name="ajouter" class="btn btn-primary">Ajouter</button>
        </div>
    </div>
</form>

<!-- Liste des utilisateurs -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Modifier le rôle</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($u = mysqli_fetch_assoc($res)): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td>
                <?php
                switch ($u['id_role']) {
                    case 1: echo "Administrateur"; break;
                    case 2: echo "Caissier"; break;
                    case 3: echo "Vendeur"; break;
                    default: echo "Inconnu";
                }
                ?>
            </td>
            <td>
                <form method="post" class="d-flex gap-2">
                    <input type="hidden" name="id_utilisateur" value="<?= $u['id_utilisateur'] ?>">
                    <select name="role" class="form-select form-select-sm">
                        <option value="1" <?= $u['id_role']==1?'selected':'' ?>>Administrateur</option>
                        <option value="2" <?= $u['id_role']==2?'selected':'' ?>>Caissier</option>
                        <option value="3" <?= $u['id_role']==3?'selected':'' ?>>Vendeur</option>
                    </select>
                    <button type="submit" name="modifier_role" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include("footer.php"); ?>