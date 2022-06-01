<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
$curr_user = $_SESSION['user'];
$id='10';
    ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>ETUDIANT</title>
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
                    <h3 class="text-dark mb-0">information sur l'etudiant n°  <?php echo($id)?> <br></h3>
                  
                </div>

                <div class="card shadow">
                        <div class="card-body">
                             <?php
                            try {
                                $query = "SELECT  p.*,e.*  FROM person p ,etudiant e
                                         where p.id='$id' and p.id= e.id";


                                $stmt = $pdo->query($query);
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                          
                                <table class="table">
                                   
                                    <tbody style="width: 913.6px;">
                                        <tr class="d-flex flex-column flex-grow-1 style="padding: -2px;">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">Nom </td>
                                            <td class="text-end text-uppercase"><?php echo $value['lname']; ?></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">PRENOM</td>
                                            <td class="text-end text-uppercase"><?php echo $value['fname']; ?></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">CIN</td>
                                            <td class="text-end text-uppercase"><?php echo $value['cin']; ?></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">CNE</td>
                                            <td class="text-end text-uppercase"><?php echo $value['cne']; ?></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">PROMOTION</td>
                                            <td class="text-end text-uppercase"><?php echo $value['promotion'] . "/" . ($value['promotion'] + 3); ?></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">EMAIL</td>
                                            <td class="text-end text-uppercase"><?php echo $value['email']; ?><br></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">TELEPHONE</td>
                                            <td class="text-end text-uppercase"><?php echo $value['phone']; ?><br></td>
                                        </tr>
                                        <tr class="d-flex flex-column flex-grow-1">
                                            <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">DATE DE NAISSANCE</td>
                                            <td class="text-end text-uppercase"><?php echo $value['date_naiss']; ?><br></td>
                                        </tr>
                                       
                                    </tbody>
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
                    <br>

                <div class="card shadow">
                    <div class="card-header py-3">
                    <h3 class="text-dark mb-0">Liste des stages</h3>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                               <thead>
                                <th>id de stage</th>
                                <th>description</th>
                                <th>date debut</th>
                                <th>date fin</th>
                                <th>Entreprise</th>
                                <th>Encadrant</th>
                                <th>Note Encadrant</th>
                                <th>Note Encadrant Exterieur</th>
                                <th>numero de condidature</th>
                                <th>Status</th>
                                </thead>

                            </thead>
                            <?php
                            try {
                               
                                $query = "SELECT c.id as cond_id,s.id as stage_id,s.*, p.lname
                                ,e.name
                                 FROM candidature c ,stage s,person p, entreprise e
                                where  s.stagiaire_id =$id and c.id=s.candidature_id  and 
                                s.encadrant_id=p.id and e.id=s.entreprise_id ";

                                $stmt = $pdo->query($query);
                               
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                        <td><?php echo $value['stage_id']; ?></td>
                                                <td><?php echo $value['description']; ?></td>
                                                <td><?php echo $value['start']; ?></td>
                                                <td><?php echo $value['end']; ?></td>
                                                <td><?php echo $value['name']; ?></td>
                                                <td><?php echo $value['lname']; ?></td>
                                                <td><?php echo $value['encardant_note']; ?></td>
                                                <td><?php echo $value['encadrant_ext_note']; ?></td>
                                                <td><?php echo $value['cond_id']; ?></td>
                                                <td><?php echo $value['statue']; ?></td>
                                                    
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



