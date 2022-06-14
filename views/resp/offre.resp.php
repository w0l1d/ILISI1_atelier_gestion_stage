<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
require_once(__DIR__ . '/../../views/switcher.php');
$curr_user = $_SESSION['user'];

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


        $query = "INSERT INTO offre 
                    (id, created_date, delai_offre, description, duree_stage, end_stage, nbr_stagiaire,
                     start_stage, statue, title, type_stage, updated_date, entreprise_id, formation_id)
                    VALUES (null,cast(NOW() as datetime ),:delai_offre,:description,:duree_stage,:end_stage,:nbr_stagiaire,
                            :start_stage,:statue,:title,:type_stage,cast(NOW() as datetime ),:entreprise_id, :formation_id)";
        $stmt = $pdo->prepare($query);

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
        $stmt->bindParam(':formation_id', $formation_id);

        if ($stmt->execute())
            $msg = "L'Offre est bien cree";
        else
            $error = "Erreur : Offre n'est pas cree";

    } else
        $error = "Veuillez entrer les champs obligatoires";
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
                    <h3 class="text-dark mb-0">Offres de stages<br></h3>
                    <button class="btn btn-primary d-none d-sm-block d-md-block" type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i>&nbsp;ajouter offre
                    </button>
                    <button class="btn btn-primary d-block d-sm-none d-md-none" type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i></button>
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
                        <p class="text-primary m-0 fw-bold">Offres</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                            <thead>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Statue</th>
                            <th>Type</th>
                            <th>Entreprise</th>
                            <th>Dernier Delai</th>
                            <th>Duree en jours</th>
                            <th>Nombre de stagiaires</th>
                            <th>Date Debut</th>
                            <th>Date Fin</th>
                            <th>Description</th>
                            <th>Date de creation</th>
                            <th>Date de Maj</th>
                            <th class="all">Action</th>
                            </thead>
                            <?php
                            try {
                               
                                $query = "SELECT o.id, o.created_date, o.delai_offre, o.description, 
                                            o.duree_stage, o.end_stage, o.nbr_stagiaire, o.start_stage,
                                            o.statue, o.title, o.updated_date, o.formation_id,
                                            o.type_stage, e.short_name, e.name,o.entreprise_id
                                            FROM offre o, entreprise e WHERE o.formation_id = :formation_id
                                            AND o.entreprise_id = e.id";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':formation_id', $curr_user['formation_id']);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['id']; ?></td>
                                            <td><?php echo $value['title']; ?></td>
                                            <td><?php echo $value['statue']; ?></td>
                                            <td><?php echo $value['type_stage']; ?></td>
                                            <td data-bs-toggle="tooltip" title="<?php echo $value['name']; ?>">
                                                <a href="/entreprises/view?id=<?php echo $value['entreprise_id'];
                                                  ?>">
                                                    <?php echo $value['short_name']; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $value['delai_offre']; ?></td>
                                            <td><?php echo $value['duree_stage']; ?></td>
                                            <td><?php echo $value['nbr_stagiaire']; ?></td>
                                            <td><?php echo $value['start_stage']; ?></td>
                                            <td><?php echo $value['end_stage']; ?></td>
                                            <td><?php echo $value['description']; ?></td>
                                            <td><?php echo $value['created_date']; ?></td>
                                            <td><?php echo $value['updated_date']; ?></td>
                                            <td>
                                                <a class="btn btn-secondary bg-secondary btn-circle btn-sm"
                                                   href="/offres/view?id=<?php echo $value['id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-primary bg-primary btn-circle btn-sm"
                                                   href="/offres/update?id=<?php echo $value['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else
                                    echo "Nothing found";
                            } catch (Exception $e) {
                                echo 'Erreur : ' . $e->getMessage();
                            }
                            ?>

                        </table>
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


<!--ADD OFFRE MODAL-->
<form class="d-flex flex-column flex-fill justify-content-around align-content-start"
      action="/offres" method="post" style="font-size: calc(0.5em + 1vmin);">
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter Offre de stage</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titre<span
                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                        <input class="form-control" type="text"
                               required="" name="title"
                               placeholder="titre" maxlength="149"
                               minlength="5">
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
                                        <option value="<?php echo $value['id']; ?>">
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
                                       min="1" name="nbr_stagiaire">
                            </div>
                            <div class="col-auto flex-grow-1 mb-2">
                                <label class="form-label">Delai de stage<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <input class="form-control" type="date" name="delai_offre" required="">
                            </div>
                            <div class="col-auto col-lg-auto flex-grow-1 mb-2">
                                <label class="form-label">Type de stage<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <select class="form-select" required="" name="type_stage">
                                    <option value="PFE" selected="">PFE</option>
                                    <option value="PFA">PFA</option>
                                    <option value="INIT">stage d'initiation</option>
                                    <option value="SUMMER">stage d'ete</option>
                                </select>
                            </div>
                            <div class="col-auto col-lg-auto flex-grow-1  mb-2">
                                <label class="form-label">Status<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <select class="form-select" required name="status">
                                    <option value="NEW" selected="">Nouveau</option>
                                    <option value="CLOSED">Ferme</option>
                                    <option value="CANCELLED">Annule</option>
                                    <option value="FULL">Complet</option>
                                </select>
                            </div>
                            <div class="col-auto flex-grow-1 mb-2">
                                <label class="form-label flex-grow-1">Duree de stage<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <input class="form-control" type="number" required="" min="30"
                                       name="duree_stage" placeholder="duree en jour">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mb-3">
                        <legend>Period de stage</legend>
                        <div class="row">
                            <div class="col mb-2">
                                <label class="form-label">debut<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <input class="form-control" type="date" name="start_stage" required="">
                            </div>
                            <div class="col mb-2">
                                <label class="form-label">fin<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <input class="form-control" type="date" name="end_stage" required="">
                            </div>
                        </div>
                    </fieldset>
                    <div class="mb-3">
                        <label class="form-label">Description<span
                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                        <textarea class="border rounded form-control"
                                  placeholder="Description" name="description"
                                  maxlength="254"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/datatable/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>
<script src="/assets/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="/assets/datatable/js/dataTables.responsive.min.js"></script>
<script src="/assets/datatable/js/responsive.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details for ' + data[0] + ' ' + data[1];
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
                }
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"
            }
        });
    });
</script>

</body>

</html>



