<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id'])) {
    header('Location: /stages');
}
 $stage_id = $_GET['id'];
 

try {
    $query = "SELECT   e.*,s.* , pe.* ,ps.*,e.id as entreprise_id , s.id as staage_id  ,
                ps.id as etud_id  ,pe.id as ens_id , s.statue as stage_statue, s.created_date as stage_created_date,
                ps.fname as etu_fname,ps.lname as etu_lname, pe.fname as ens_fname,pe.lname as ens_lname
                FROM  entreprise e,stage s,person ps,person pe
                WHERE s.id =:stage_id and s.stagiaire_id=ps.id and e.id=s.entreprise_id and pe.id=s.encadrant_id
                " ;
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    $stage = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($stage)) {
        $error = "stage `$stage_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
//  header('Location: /stages');
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
                    <h3 class="text-dark mb-0">information sur le stage n° <?php echo $stage_id ?> <br></h3>

                </div>

                <div class="card shadow">
                     <div class="card-header">
                     <h5 class="text-dark mb-0">stage  <br></h5>
                     </div>
                    <div class="card-body">
                        <table class="table" style="font-size: calc(0.5em + 1vmin);">
                            <tbody style="width: 913.6px;">
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    numero de stage
                                </td>
                                <td class="text-center "><?php echo $stage['staage_id']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    statue du stage
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['stage_statue']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                stagiaire
                                </td>
                                <td class="text-center"><?php echo $stage['stagiaire_id'].":".$stage['etu_fname']. $stage['etu_lname']; ?></td>
                            </tr>
                           
                            
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Entreprise
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['entreprise_id'].":".$stage['short_name']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    duree de stage
                                </td>
                                <td class="text-center text-uppercase"><?php 
                                

                                $date1 = new DateTime($stage['start']);
                                $date2 = new DateTime($stage['end']);

                                $diff = $date2->diff($date1);

                                echo  $diff->days . " days ";

                                
                                 ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    debut
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['start']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    fin
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['end']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    date de creation de le stage 
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['stage_created_date']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                   Encadrant 
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['encadrant_id'].":".$stage['ens_fname']. $stage['ens_lname']; ?><br></td>
                            </tr>
                            <?php if( $stage['statue'] === "FINISHED") {?>
                                <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Note de l'Encadrant
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['encardant_note']; ?><br></td>
                            </tr>       
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Note de l'Encadrant Exterieur
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['encadrant_ext_note']; ?><br></td>
                            </tr>
                           <?php }?>
                           <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                   Description
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['description']; ?><br></td>
                            </tr>

                            <?php if(!empty( $stage['candidature_id'])){?>
                                <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    numero de candidature
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['candidature_id']; ?><br></td>
                            </tr>
                            <?php  try {  $query = "SELECT c.*,o.*,o.id as offre_id, c.id as candidature_id ,
                                            c.created_date as cand_created_date, o.created_date as offre_created_date,
                                            c.status as cand_statue, o.statue as off_statue
                                            FROM offre o, candidature c  where  c.id=:candidature AND o.id=c.offre_id";
                                 $stmt = $pdo->prepare($query);
                                 $stmt->bindParam(':candidature', $stage['candidature_id']);
                                  $stmt->execute();
                                  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                  if (!empty($rows)) {
                                      foreach ($rows as $key => $value) {
                                          ?>
                           
                              <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    numero d'offre
                                </td>
                                <td class="text-center text-uppercase"><?php echo $value['offre_id']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    date de creation de l'offre
                                </td>
                                <td class="text-center text-uppercase"><?php echo $value['offre_created_date']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    date de creation de candidature
                                </td>
                                <td class="text-center text-uppercase"><?php echo $value['cand_created_date']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    statue de candidature
                                </td>
                                <td class="text-center text-uppercase"><?php echo $value['cand_statue']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    statue de l'offre
                                </td>
                                <td class="text-center text-uppercase"><?php echo $value['off_statue']; ?><br></td>
                            </tr>
                               
                                <?php
                                    }
                                } else
                                    echo "Nothing found";
                            } catch (Exception $e) {
                                echo 'Erreur : ' . $e->getMessage();
                            }
                        }
                            ?>
                           
                            
                            
                            </tbody>
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



