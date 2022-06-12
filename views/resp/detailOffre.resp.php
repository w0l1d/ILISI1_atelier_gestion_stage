<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
require_once(__DIR__ . '/../../views/switcher.php');
$curr_user = $_SESSION['user'];
if (empty($_GET['id'])) {
    header('Location: /offres');
}
$offre_id = $_GET['id'];
$statue_att = "WAITING";

try {
    $query = "SELECT o.*, e.name FROM offre o, entreprise e WHERE o.id = :id AND o.entreprise_id = e.id";
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
                    <h3 class="text-dark mb-0">information sur l'offre n° <?php echo $offre_id ?> <br></h3>

                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <table class="table" style="font-size: calc(0.5em + 1vmin);">
                            <tbody style="width: 913.6px;">
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    TITRE DE L'OFFRE
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['title']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    STATUE
                                </td>
                                <td class="text-center text-uppercase"><?php echo  switch_offre($offre['statue']); ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    TYPE DE STAGE
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['type_stage']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    NOMBRE DE STAGIAIRE
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['nbr_stagiaire']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DELAI&nbsp; DE STAGE
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['duree_stage']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    ENTREPRISE
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['name']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DUREE DE STAGE
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['duree_stage']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DEBUT
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['start_stage']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    FIN
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['end_stage']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DATE DE CREATION
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['created_date']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DATE DE MODIFICATION
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['updated_date']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DESCRIPTION
                                </td>
                                <td class="text-center text-uppercase"><?php echo $offre['description']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <h3 class="text-dark mb-0">LISTE DES CONDIDATURES</h3>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                            <thead>
                            <th>CONDIDATURE ID</th>
                            <th>ETUDIANT ID</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>STATUS DE CONDIDATURE</th>
                            <th>DATE DE CREATION</th>
                            <th>DATE DE MODIFICATION</th>
                            </thead>
                            <?php
                            try {
                                $query = "SELECT p.*, c.*, e.*, e.id AS etudiantid,
                                            c.id AS candidatureId FROM etudiant e, candidature c, person p
                                WHERE offre_id = :offre_id AND c.etudiant_id = p.id AND e.id = c.etudiant_id";
                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':offre_id', $offre_id);
                                $stmt->execute();
                                $condidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($condidatures as $key => $value) {
                                    ?>
                                    <tr>
                                        <td><?php echo $value['candidatureId']; ?></td>
                                        <td><?php echo $value['etudiantid']; ?></td>
                                        <td><?php echo $value['lname']; ?></td>
                                        <td><?php echo $value['fname']; ?></td>
                                        <td><?php echo $value['status'];
                                            if (strcmp($value['status'], $statue_att) == 0) {
                                                echo " ( " . $value['position'] . " )";
                                            }
                                            ?>
                                        </td>

                                        <td><?php echo $value['created_date']; ?></td>
                                        <td><?php echo $value['updated_date']; ?></td>

                                    </tr>
                                    <?php
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
                            let data = row.data();
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



