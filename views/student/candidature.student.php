<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
$curr_user = $_SESSION['user'];
$statue_att = "WAITING";
require_once(__DIR__ . '/../../views/switcher.php');
?>



<?php
if (!empty($_GET['agree'])) {
    $candidature = $_GET['agree'];
    try {
        $pdo->beginTransaction();
        $query = "UPDATE candidature SET status = 'AGREED' where id = :id AND status = 'ACCEPTED'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $candidature);
        if (!$stmt->execute()) {
            $pdo->rollBack();
            $error = "Offre n'est pas acceptee";
            goto skip_process;
        }

        $query = "UPDATE candidature SET status = 'NAGREED' 
                   WHERE etudiant_id = (SELECT etudiant_id FROM candidature 
                                                           WHERE id = :id) 
                     AND status = 'ACCEPTED'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $candidature);
        if (!$stmt->execute()) {
            $pdo->rollBack();
            $error = "Offre n'est pas acceptee";
            goto skip_process;
        }
        $msg = "Congratulation : l'Offre est acceptee, le reponsable attribuer un encadrant";
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
} else if (!empty($_GET['disagree'])) {
    $candidature = $_GET['disagree'];
    try {
        $pdo->beginTransaction();
        $query = "UPDATE candidature SET status = 'NAGREED' where id = :id AND status = 'ACCEPTED'";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $candidature);
        if (!$stmt->execute()) {
            $pdo->rollBack();
            $error = "Offre n'est pas refusee";
            goto skip_process;
        }

        $query = "UPDATE candidature SET status = 'ACCEPTED' 
                   WHERE id = (SELECT id FROM candidature c 
                                         WHERE c.status LIKE 'WAITING' 
                                           AND c.offre_id = (SELECT offre_id 
                                                             FROM candidature 
                                                             WHERE id = :candidature_id)  
                                           AND (c.position, c.offre_id) in 
                                               (SELECT min(c2.position), c2.offre_id 
                                                FROM candidature c2 GROUP BY c2.offre_id))";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":candidature_id", $candidature);
        if (!$stmt->execute()) {
            $pdo->rollBack();
            $error = "Offre n'est pas refusee";
            goto skip_process;
        }

        $warning = "l'Offre est refusee";
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
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
    <?php require_once 'parts/sidebar.php' ?>
    <div class="d-flex flex-column" id="content-wrapper" style="font-size: calc(0.5em + 1vmin);">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>
            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Mes candidatures<br></h3>
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
                <?php } elseif (!empty($warning)) { ?>
                    <div class="alert alert-warning" role="alert">
                    <span>
                        <?php echo $warning; ?>
                    </span>
                    </div>
                <?php } ?>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Liste</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                            <thead>
                            <th>CANDIDATURE ID</th>
                            <th>OFFRE ID</th>
                            <th>TITRE DE L'OFFRE</th>
                            <th>STATUE</th>
                            <th>DATE DE CREATION</th>
                            <th>DATE DE MAJ</th>
                            <th class="all">Action</th>
                            </thead>
                            <?php
                            try {

                                $query = "SELECT c.id as candidature_id,c.created_date,c.status,c.updated_date,
                                        c.offre_id,c.position,o.title
                                            FROM offre o, candidature c WHERE  c.etudiant_id = :id_etudiant  AND o.id =c.offre_id";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':id_etudiant', $curr_user['id']);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['candidature_id']; ?></td>
                                            <td><?php echo $value['offre_id']; ?></td>
                                            <td><?php echo $value['title']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php
                                                switch ($value['status']) {
                                                    case 'APPLIED':
                                                        echo 'info';
                                                        break;
                                                    case 'CANCELED':
                                                        echo 'secondary';
                                                        break;
                                                    case 'ACCEPTED':
                                                        echo 'gradient-success';
                                                        break;
                                                    case 'WAITING':
                                                        echo 'gradient-info';
                                                        break;
                                                    case 'NACCEPTED':
                                                        echo 'gradient-warning';
                                                        break;
                                                    case 'AGREED':
                                                        echo 'success';
                                                        break;
                                                    case 'NAGREED':
                                                        echo 'warning';
                                                        break;
                                                }

                                                ?> text-uppercase font-monospace" bs-cut="1">
                                                    <?php echo switch_candidature($value['status']);
                                                    if (strcmp($value['status'], $statue_att) == 0) {
                                                        echo " ( " . $value['position'] . " )";
                                                    }
                                                    ?>
                                                </span>

                                            </td>
                                            <td><?php echo $value['created_date']; ?></td>
                                            <td><?php echo $value['updated_date']; ?></td>
                                            <td>
                                                <a class="btn btn-secondary bg-secondary btn-circle btn-sm"
                                                   href="/offres/view?id=<?php echo $value['offre_id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($value['status'] === 'ACCEPTED') { ?>
                                                    <a class="btn btn-success bg-success btn-circle btn-sm"
                                                       href="/candidatures?agree=<?php echo $value['candidature_id']; ?>">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    <a class="btn btn-danger bg-danger btn-circle btn-sm"
                                                       href="/candidatures?disagree=<?php echo $value['candidature_id']; ?>">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
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



