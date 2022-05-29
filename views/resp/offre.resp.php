<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Offres</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/datatable/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/datatable/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
</head>

<body id="page-top">
<div id="wrapper">
    <?php
    require_once 'parts/sidebar.php'
    ?>
    <div class="d-flex flex-column" id="content-wrapper" style="font-size: calc(0.5em + 1vmin);">
        <div id="content">
            <?php require_once 'parts/navbar.html' ?>
            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Offres de stages<br></h3>
                    <button class="btn btn-primary d-none d-sm-block d-md-block" type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i>&nbsp;ajouter offre
                    </button>
                    <button class="btn btn-primary d-block d-sm-none d-md-none" type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i></button>
                </div>
                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Offres</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                            <thead>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type Stage</th>
                            <th>Nombre Stagiaire</th>
                            <th>Description</th>
                            <th>Duree Stage</th>
                            <th>Statue</th>
                            </thead>
                            <?php
                            try {
                                $query = "SELECT id, created_date, delai_offre, description, duree_stage, end_stage,
                                nbr_stagiaire, start_stage, statue, title, updated_date, entreprise_id,
                                formation_id, type_stage FROM offre e
                                where formation_id = (SELECT id FROM formation
                                                                WHERE responsable_id = :id_resp limit 1)";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam('id_resp', $curr_user['id']);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['id']; ?></td>
                                            <td><?php echo $value['title']; ?></td>
                                            <td><?php echo $value['type_stage']; ?></td>
                                            <td><?php echo $value['nbr_stagiaire']; ?></td>
                                            <td><?php echo $value['description']; ?></td>
                                            <td><?php echo $value['duree_stage']; ?></td>
                                            <td><?php echo $value['statue']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
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
<div class="modal fade" role="dialog" tabindex="-1" id="modal-1" style="font-size: calc(0.5em + 1vmin);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ajouter Offre de stage</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                      action="/offres" method="post">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input class="form-control" type="text"
                               required="" name="title"
                               placeholder="titre" maxlength="149"
                               minlength="5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Entreprise</label>
                        <select class="form-select flex-grow-1" required="">

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
                                <label class="form-label flex-grow-1">Nombre de stagiaire</label>
                                <input class="form-control" type="number" required=""
                                       min="1" name="nbr_stagiaire">
                            </div>
                            <div class="col-auto flex-grow-1 mb-2">
                                <label class="form-label">Delai de stage</label>
                                <input class="form-control" type="date" name="delai_stage" required="">
                            </div>
                            <div class="col-auto col-lg-auto flex-grow-1 mb-2">
                                <label class="form-label">Type de stage</label>
                                <select class="form-select" required="" name="status">
                                    <option value="PFE" selected="">PFE</option>
                                    <option value="PFA">PFA</option>
                                    <option value="INIT">stage d'initiation</option>
                                    <option value="SUMMER">stage d'ete</option>
                                </select>
                            </div>
                            <div class="col-auto col-lg-auto flex-grow-1  mb-2">
                                <label class="form-label">Status</label>
                                <select class="form-select" required name="status">
                                    <option value="NEW" selected="">Nouveau</option>
                                    <option value="CLOSED">Ferme</option>
                                    <option value="CANCELLED">Annule</option>
                                    <option value="FULL">Complet</option>
                                </select>
                            </div>
                            <div class="col-auto flex-grow-1 mb-2">
                                <label class="form-label flex-grow-1">Duree de stage</label>
                                <input class="form-control" type="number" required="" min="30"
                                       name="duree" placeholder="duree en jour">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="mb-3">
                        <legend>Period de stage</legend>
                        <div class="row">
                            <div class="col mb-2">
                                <label class="form-label">debut</label>
                                <input class="form-control" type="date" name="debut_stage" required="">
                            </div>
                            <div class="col mb-2">
                                <label class="form-label">fin</label>
                                <input class="form-control" type="date" name="fin_stage" required="">
                            </div>
                        </div>
                    </fieldset>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="border rounded form-control" placeholder="Description" name="description"
                                  maxlength="254"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fermer</button>
                <button class="btn btn-primary" type="button">Ajouter</button>
            </div>
        </div>
    </div>
</div>


<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/theme.js"></script>
<script src="assets/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/datatable/js/dataTables.responsive.min.js"></script>
<script src="assets/datatable/js/responsive.bootstrap5.min.js"></script>

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



