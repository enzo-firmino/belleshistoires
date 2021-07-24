<?php
$url = $_SERVER['REQUEST_URI'];
$tab = explode("/", $url);
$url = end($tab);

?>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div style="display: flex">
        <a href="index.php"> <img class="logo navbar-brand" src="image/logo.png" alt="Logo Pole emploi"></a>
        <a class='nav-link <?php if (preg_match("#index.php#", $url)) {
            echo "underline'";
        } ?>' href="index.php">Accueil</a>
        <a class='nav-link <?php if (preg_match("#ajouter.php#", $url) && !isset($_POST['histoire'])) {
            echo "underline'";
        } ?>' href="ajouter.php">Ajouter</a>
        <?php if (isset($_SESSION['admin'])) {
            echo '<a class="nav-link';
            if (preg_match("#recherche.php#", $url)) {
                echo ' underline';
            }
            echo ' "href="recherche.php">Recherche</a>';
        } ?>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin']['super_admin'] == 1) { ?>
            <div class="dropdown nav-link">
                <span>Admin</span>
                <div class="dropdown-content">
                    <a class=' <?php if (preg_match("#admin_users.php#", $url)) {
                        echo "underline'";
                    } ?>' href="admin_users.php">Utilisateurs</a>
                    <a class=' <?php if (preg_match("#admin_mots_publics.php#", $url)) {
                        echo "underline'";
                    } ?>' href="admin_mots_publics.php">Mots clés & publics cibles</a>
                </div>
            </div>
        <?php } ?>
        <a class='nav-link <?php if (preg_match("#identification.php#", $url)) {echo "underline'";} ?>' href="identification.php">
            <?php if (!isset($_SESSION['admin']) ){echo "Connexion";} else {echo "Déconnexion";} ?>
        </a>

    </div>
</nav>


