<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id']))
    header('Location: /offres');

$offre_id = $_GET['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['title']) &&
        !empty($_POST['entreprise_id']) &&
        !empty($_POST['nbr_stagiaire']) &&
        !empty($_POST['delai_offre']) &&
        !empty($_POST['type_stage']) &&
        !empty($_POST['status']) &&
        !empty($_POST['duree_stage']) &&
        !empty($_POST['start_stage']) &&
        !empty($_POST['end_stage']) &&
        !empty($_POST['description'])) {

        $delai_offre = $_POST['delai_offre'];
        $description = $_POST['description'];
        $duree_stage = $_POST['duree_stage'];
        $end_stage = $_POST['end_stage'];
        $nbr_stagiaire = $_POST['nbr_stagiaire'];
        $start_stage = $_POST['start_stage'];
        $statue = $_POST['status'];
        $title = $_POST['title'];
        $type_stage = $_POST['type_stage'];
        $entreprise_id = $_POST['entreprise_id'];
        $formation_id = $curr_user['formation_id'];


        $query = "UPDATE offre set delai_offre = :delai_offre, `description` = :description,
                 duree_stage = :duree_stage, end_stage = :end_stage,
                 nbr_stagiaire = :nbr_stagiaire, start_stage = :start_stage, 
                 statue = :statue, title = :title, type_stage = :type_stage,
                 updated_date = NOW(), entreprise_id = :entreprise_id  WHERE id = :id";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':id', $offre_id);
        $stmt->bindParam(':delai_offre', $delai_offre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':duree_stage', $duree_stage);
        $stmt->bindParam(':end_stage', $end_stage);
        $stmt->bindParam(':nbr_stagiaire', $nbr_stagiaire);
        $stmt->bindParam(':start_stage', $start_stage);
        $stmt->bindParam(':statue', $statue);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':type_stage', $type_stage);
        $stmt->bindParam(':entreprise_id', $entreprise_id);

        if ($stmt->execute())
            $msg = "L'Offre est bien Mise a jour";
        else
            $error = "Erreur : Maj n'est effectee";

    } else
        $error = "Veuillez entrer les champs obligatoires";
}
try {
    $query = "SELECT * FROM offre WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $offre_id);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($offre)) {
        $error = "Offre `$offre_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    header('Location: /offres');
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Offres</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/datatable/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/datatable/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
</head>

<body id="page-top">
<div id="wrapper">
    <?php
    require_once 'parts/sidebar.php'
    ?>
    <div class="d-flex flex-column" id="content-wrapper" style="font-size: calc(0.5em + 1vmin);">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>
            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0"><br></h3>

                </div>

                <?php
                if (!empty($error)) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                    <span>
                        <strong>Erreur : </strong>
                        <?php echo $error; ?>
                    </span>
                    </div>
                <?php } elseif (!empty($msg)) { ?>
                    <div class="alert alert-success" role="alert">
                    <span>
                        <?php echo $msg; ?>
                    </span>
                    </div>
                <?php } ?>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Modifier Offres</p>
                    </div>
                    <div class="card-body">
                        <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                              method="post" style="font-size: calc(0.5em + 1vmin);">
                            <div class="mb-3">
                                <label class="form-label">Titre<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <input class="form-control" type="text"
                                       required="" name="title"
                                       placeholder="titre" maxlength="149"
                                       minlength="5" value="<?php echo $offre['title']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Entreprise<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <select name="entreprise_id" class="form-select flex-grow-1" required="">

                                    <?php
                                    try {
                                        $query = "SELECT id, name, short_name FROM entreprise e";

                                        $stmt = $pdo->prepare($query);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if (!empty($rows)) {
                                            foreach ($rows as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['id']; ?>"
                                                    <?php if ($value['id'] === $offre['entreprise_id'])
                                                        echo ' selected';?>
                                                >
                                                    <?php echo "{$value['short_name']}: {$value['name']}"; ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo 'Erreur : ' . $e->getMessage();
                                    }
                                    ?>

                                </select>
                            </div>
                            <fieldset class="mb-3">
                                <legend>Specification</legend>
                                <div class="row row-cols-2">
                                    <div class="col-auto flex-grow-1 mb-2">
                                        <label class="form-label flex-grow-1">Nombre de stagiaire<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <input class="form-control" type="number" required=""
                                               min="1" name="nbr_stagiaire" value="<?php echo $offre['nbr_stagiaire']; ?>">
                                    </div>
                                    <div class="col-auto flex-grow-1 mb-2">
                                        <label class="form-label">Delai de stage<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <input class="form-control" type="date" name="delai_offre" required=""
                                               value="<?php echo $offre['delai_offre']; ?>">
                                    </div>
                                    <div class="col-auto col-lg-auto flex-grow-1 mb-2">
                                        <label class="form-label">Type de stage<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <?php echo "typ d'offre : " . $offre['type_stage']?>
                                        <select class="form-select" required="" name="type_stage">
                                            <option value="PFE" <?php if ($offre['type_stage'] === "PFE")
                                                echo 'selected';?>>PFE</option>
                                            <option value="PFA" <?php if ($offre['type_stage'] === "PFA")
                                                echo 'selected';?>>PFA</option>
                                            <option value="INIT" <?php if ($offre['type_stage'] === 'INIT')
                                                echo 'selected'; ?>> stage d'initiation</option>
                                            <option value="SUMMER" <?php if ($offre['type_stage'] === "SUMMER")
                                                echo 'selected';?>>stage d'ete</option>
                                        </select>
                                    </div>
                                    <div class="col-auto col-lg-auto flex-grow-1  mb-2">
                                        <label class="form-label">Status<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <select class="form-select" required name="status">
                                            <option value="NEW" <?php if ($offre['statue'] === 'NEW')
                                                echo 'selected';?>>Nouveau</option>
                                            <option value="CLOSED" <?php if ($offre['statue'] === 'CLOSED')
                                                echo 'selected';?>>Ferme</option>
                                            <option value="CANCELLED" <?php if ($offre['statue'] === 'CANCELLED')
                                                echo 'selected';?>>Annule</option>
                                            <option value="FULL" <?php if ($offre['statue'] === 'FULL')
                                                echo 'selected';?>>Complet</option>
                                            <option value="WAITING_RESPONSE" <?php if ($offre['statue'] === 'WAITING_RESPONSE')
                                                echo 'selected';?>>en Attente..Reponse des etudiants</option>
                                            <option value="WAITING_RESULT" <?php if ($offre['statue'] === 'WAITING_RESULT')
                                                echo 'selected';?>>en Attente..Reponse de l'entreprise</option>
                                        </select>
                                    </div>
                                    <div class="col-auto flex-grow-1 mb-2">
                                        <label class="form-label flex-grow-1">Duree de stage<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <input class="form-control" type="number" required="" min="30"
                                               name="duree_stage" placeholder="duree en jour"
                                               value="<?php echo $offre['duree_stage']; ?>">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="mb-3">
                                <legend>Period de stage</legend>
                                <div class="row">
                                    <div class="col mb-2">
                                        <label class="form-label">debut<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <input class="form-control" type="date" name="start_stage"
                                               required="" value="<?php echo $offre['start_stage']; ?>">
                                    </div>
                                    <div class="col mb-2">
                                        <label class="form-label">fin<span
                                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                        <input class="form-control" type="date" name="end_stage"
                                               required="" value="<?php echo $offre['end_stage']; ?>">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="mb-3">
                                <label class="form-label">Description<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <textarea class="border rounded form-control"
                                          placeholder="Description" name="description"
                                          maxlength="254"><?php echo $offre['description']; ?></textarea>
                            </div>
                            <button class="btn btn-primary" type="submit">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © Gestion de stage 2022</span></div>
            </div>
        </footer>
    </div>
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
</div>


<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>


</body>

</html>



