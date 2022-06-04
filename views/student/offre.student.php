<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['offreid']) {

        $student_id = $curr_user ['id'];
        $offre_id = $_POST['offreid'];
        $statue = "applied";

        $query = "SELECT c.id FROM candidature c  WHERE offre_id = $offre_id
                     AND etudiant_id = $student_id ";

        $stmt = $pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            $error = "  vous avez deja postuler dans cette offre N° :" . $offre_id;
        } else {
            $query = "INSERT INTO candidature (id, created_date, status, updated_date,etudiant_id,offre_id,position)
                    VALUES (null,cast(now() as datetime),:statue,cast(now() as datetime),:student_id,:offre_id,NULL)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':statue', $statue);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':offre_id', $offre_id);

            if ($stmt->execute())
                $msg = "vous avez postule à l'offre `$offre_id`";
            else
                $error = "ne peux pas postuler";
        }
    }
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
            <?php require_once 'parts/navbar.html' ?>
            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Offres de stages<br></h3>

                </div>


                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">LISTES DE OFFRES</p>
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
                                            o.statue, (SELECT c.status FROM candidature c WHERE c.offre_id = o.id AND c.etudiant_id = :id_etud) AS candidature_statue,
                                            o.title, o.updated_date, o.formation_id,
                                            o.type_stage, e.short_name, e.name
                                            FROM offre o, entreprise e WHERE o.formation_id = :formation_id
                                            AND o.entreprise_id = e.id  and o.delai_offre >= cast(now() as date)";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':formation_id', $curr_user['formation_id']);
                                $stmt->bindParam(':id', $curr_user['id']);
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
                                                <?php echo $value['short_name']; ?>
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
                                                <?php if (empty($value['candidature_statue'])) { ?>
                                                    <?php if ($value['statue'] === 'NEW') { ?>
                                                        <form method='POST' action="/offres">
                                                            <input type="hidden" name="offreid"
                                                                   value="<?php echo $value['id']; ?>"/>
                                                            <input class="btn btn-primary btn-sm text-uppercase"
                                                                   type="submit" name="button1" value="postuler"/>
                                                        </form>
                                                    <?php } else { ?>
                                                        <span class="badge bg-secondary text-uppercase font-monospace"
                                                              bs-cut="1"><?php echo $value['statue']; ?></span>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <span class="badge bg-success text-uppercase font-monospace"
                                                          bs-cut="1"><?php echo $value['candidature_statue']; ?></span>
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
                            <?php
                            if (!empty($error)) {
                                ?>
                                <div class="alert alert-danger" role="alert">
                    <span>
                        <strong>Erreur : </strong>
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