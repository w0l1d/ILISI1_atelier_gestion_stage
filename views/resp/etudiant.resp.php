<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];

if (!empty($_GET['validate'])) {
    $validate_id = $_GET['validate'];

    $query = "SELECT * FROM etudiant WHERE id = :validate_id;";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':validate_id', $validate_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($row)) {
        $error = "etudiant n'existe pas";
        goto skip_process;
    } elseif ($row['formation_id'] != $curr_user['formation_id']) {
        $error = "etudiant `{$row['id']}` n'appartient pas a votre formation";
        goto skip_process;
    } elseif ($row['IsValidated']) {
        $error = "etudiant `{$row['id']}` est deja valide";
        goto skip_process;
    }

    $query = "UPDATE etudiant SET IsValidated = true where id = :validate_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':validate_id', $validate_id);
    if (!$stmt->execute()) {
        $error = 'Etudiant n\'est pas Valide';
        goto skip_process;
    }

    $msg = "etudiant est valide";
}
elseif (!empty($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    try {

        $query = "SELECT * FROM etudiant WHERE id = :delete_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':delete_id', $delete_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        if (empty($row)) {
            $error = "etudiant n'existe pas";
            goto skip_process;
        } elseif ($row['formation_id'] != $curr_user['formation_id']) {
            $error = "etudiant `{$row['id']}` n'appartient pas a votre formation";
            goto skip_process;
        } elseif ($row['IsValidated']) {
            $error = "etudiant `{$row['id']}` est deja valide";
            goto skip_process;
        }

        $pdo->beginTransaction();

        $query = "DELETE FROM etudiant WHERE id = :delete_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':delete_id', $delete_id);
        if (!$stmt->execute()) {
            $pdo->rollback();
            $error = 'Etudiant n\'est pas supprime';
            goto skip_process;
        }

        $query = "DELETE FROM person WHERE id = :delete_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':delete_id', $delete_id);
        if (!$stmt->execute()) {
            $pdo->rollback();
            $error = 'Etudiant n\'est pas supprime';
            goto skip_process;
        }

        $pdo->commit();
        $msg = 'Etudiant est supprime';
    } catch (Exception $e) {
        $pdo->rollback();
        $error = $e->getMessage();
    }
}
skip_process:

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Etudiants</title>
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
            <?php require_once 'parts/navbar.html' ?>
            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Etudiants<br></h3>
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
                        <p class="text-primary m-0 fw-bold">Etudiants</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                            <thead>
                            <th>id</th>
                            <th>Etat</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>CIN</th>
                            <th>CNE</th>
                            <th>Promotion</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Date de Naissance</th>
                            <th class="all">Action</th>
                            </thead>
                            <?php
                            try {
                                $query = "SELECT p.*, e.* FROM person p, etudiant e 
                                            WHERE e.id = p.id AND e.formation_id = :formation_id";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':formation_id', $curr_user['formation_id']);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['id']; ?></td>
                                            <td>
                                                <?php if ($value['IsValidated']) { ?>
                                                    <span class="badge bg-success text-uppercase font-monospace" bs-cut="1">Valide</span>
                                                <?php } else {
                                                    ?>
                                                    <span class="badge bg-secondary text-uppercase font-monospace" bs-cut="1">Invalide</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $value['lname']; ?></td>
                                            <td><?php echo $value['fname']; ?></td>
                                            <td><?php echo $value['cin']; ?></td>
                                            <td><?php echo $value['cne']; ?></td>
                                            <td><?php echo $value['promotion'] . "/" . ($value['promotion'] + 3); ?></td>
                                            <td><?php echo $value['email']; ?></td>
                                            <td><?php echo $value['phone']; ?></td>
                                            <td><?php echo $value['date_naiss']; ?></td>
                                            <td>
                                                <?php if (!$value['IsValidated']) { ?>
                                                    <div class="btn-toolbar" bs-cut="1">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a class="btn btn-success" role="button"
                                                               href="/etudiants?validate=<?php echo $value['id']; ?>">
                                                                <i class="fa fa-check"></i></a>
                                                            <a class="btn btn-danger" role="button"
                                                               href="/etudiants?delete=<?php echo $value['id']; ?>">
                                                                <i class="far fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
                    <h4 class="modal-title">Ajouter Etudiant de stage</h4>
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



