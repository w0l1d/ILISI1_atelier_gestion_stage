<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
require_once(__DIR__ . '/../../views/switcher.php');
$accepted_candidature=null;

//accepted 
try {
    $query_acc= "SELECT c.*, c.id as candidature_id,e.short_name, o.type_stage,o.title
                FROM entreprise e,candidature c, offre o
                WHERE o.id=c.offre_id AND e.id=o.entreprise_id AND c.etudiant_id =:etud_id 
                AND c.status='ACCEPTED'";

    $stmt_acc = $pdo->prepare($query_acc);
    $stmt_acc->bindParam(':etud_id', $curr_user['id']);
    $stmt_acc->execute();
    $accepted_candidature = $stmt_acc->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

try {
    $query_ent = "SELECT e.name, e.short_name,e.email,e.logo,e.domaine,e.web_site, e.id  FROM entreprise e 
                                   
                                     ORDER BY e.id DESC limit 3";

    $stmt_ent = $pdo->prepare($query_ent);
    $stmt_ent->execute();
    $recent_companies = $stmt_ent->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

try {
    $query_offre = "SELECT o.entreprise_id as entId, o.delai_offre, o.start_stage, o.end_stage,  o.title ,o.nbr_stagiaire,e.logo, e.short_name FROM offre o ,entreprise e WHERE e.id= o.entreprise_id and  o.formation_id = :formation_id
                                   
                                     ORDER BY e.id DESC limit 3";

    $stmt_offre = $pdo->prepare($query_offre);
    $stmt_offre->bindParam(':formation_id', $curr_user['formation_id']);
    $stmt_offre->execute();
    $recent_offres = $stmt_offre->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>



<?php //stage

try {
    $query1 = "SELECT statue ,count(*) as number FROM stage GROUP BY statue";

    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php //offres
try {
    $query2 = "SELECT statue ,count(*) as number FROM offre GROUP BY statue";

    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php //candidatures
try {
    $query3 = "SELECT status ,count(*) as number FROM candidature c where c.etudiant_id= :stud_id  GROUP BY status";

    $stmt3 = $pdo->prepare($query3);
    $stmt3->bindParam(':stud_id', $curr_user['id']);
    $stmt3->execute();
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['statue', 'number'],
                <?php
                $row = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value1) {
                        echo "['" . $value1["statue"] . "'," . $value1["number"] . "],";
                    }
                }
                ?>
            ]);

            var options = {
                slices: {
                    1: {offset: 0.2},
                    2: {offset: 0.3},
                    3: {offset: 0.4},
                    4: {offset: 0.5},
                },
                colors:[  '#ab4e6b','#e5bcd9','#e5eed2','#561220',], 
            };

            var changerStatue = {
            IN_PROGRESS: 'En cours',
            FINISHED: 'Termine',
            CANCELED: 'Annule',
            DRAFT: 'planifier'
        };

        var view = new google.visualization.DataView(data);
        view.setColumns([{
          calc: function (dt, row) {
            return changerStatue[data.getValue(row, 0)];
          },
          label: 'statue',
          type: 'string'
        }, 1]);

            var chart = new google.visualization.PieChart(document.getElementById('piechartStage'));

            chart.draw(view, options);

        }


    </script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data =google.visualization.arrayToDataTable([
                [ 'statue', 'number'],
                <?php
                $row = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value2) {
                        echo "['" . $value2['statue']. "'," . $value2["number"] . "],";
                    }
                }
                ?>
            ]);

            var options = {
                is3D: true,
                colors:[  '#F2C3A7','#3D5A73','#6588A6','#D9583B',], 
            };

            var changerStatue = {
            NEW: 'Nouveau',
            CLOSED: 'Fermee',
            CANCELED: 'Annule',
            WAITING_RESPONSE: 'RÉPONSE EN ATTENTE',
            WAITING_RESULT: 'Résultat EN ATTENTE ',
            FULL: 'Plein'
        };

        var view = new google.visualization.DataView(data);
        view.setColumns([{
          calc: function (dt, row) {
            return changerStatue[data.getValue(row, 0)];
          },
          label: 'statue',
          type: 'string'
        }, 1]);

            var chart = new google.visualization.PieChart(document.getElementById('piechartOffre'));

            chart.draw(view, options);
        }


    </script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = google.visualization.arrayToDataTable([
                ['statue', 'number'],
                <?php
                $row = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value3) {
                        echo "['" . $value3["status"] . "'," . $value3["number"] . "],";
                    }
                }
                
                ?>
            ]);

            var options = {
                pieHole: 0.4,
                pieSliceTextStyle: {
                    color: 'black',
                },

            };
            var changerStatue = {
            APPLIED: 'postuler',
            NACCEPTED: 'Pas Retenu',
            CANCELED: 'Annule',
            ACCEPTED: 'Retenu',
            WAITING: 'EN ATTENTE',
            NAGREED: 'Pas Accepté',
            AGREED: 'Accepté'
        };

        var view = new google.visualization.DataView(data);
        view.setColumns([{
          calc: function (dt, row) {
            return changerStatue[data.getValue(row, 0)];
          },
          label: 'statue',
          type: 'string'
        }, 1]);

            var chart = new google.visualization.PieChart(document.getElementById('piechartCandidature'));

            chart.draw(view, options);
        }


    </script>
    
    
   
</head>

<body id="page-top">
<div id="wrapper">
    <?php require_once 'parts/sidebar.php'; ?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Tableau De Board</h3>
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
                 <!--accepted-->
                 <div class="row">
                    <?php
                        if (!empty($accepted_candidature)) {
                         foreach ($accepted_candidature as $key => $accepted){
                           ?>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        
                        <div class="card shadow mb-4 ">
                            <div class="card-header d-flex justify-content-between align-items-center  bg-warning" >
                                <h6 class="text-primary fw-bold m-0 " data-bss-hover-animate="swing"
                                    style="font-size: 18px ">Candidature Acceptée</h6>
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-link btn-sm dropdown-toggle " aria-expanded="false"
                                            data-bs-toggle="dropdown" type="button"><i
                                                class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        <p class="text-center dropdown-header">plus de détails</p><a
                                                class="dropdown-item" href="/candidature">&nbsp;afficher tous les candidatures
                                              </a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body"  >
                                <table class="table">
                                        <tr>
                                            <th> Id de Candidature</th>
                                            <td><?php echo strtoupper($accepted['candidature_id']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Titre</th>
                                            <td><?php echo strtoupper($accepted['title']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Type de stage</th>
                                            <td><?php echo $accepted['type_stage'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Societé </th>
                                            <td><?php echo $accepted['short_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>statue </th>
                                            <td><?php echo $accepted['status'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Action </th>
                                            <td>
                                            <a class="btn btn-secondary bg-success btn-circle btn-sm"
                                                   href="/offres/view?id=<?php echo $accepted['offre_id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-success bg-success btn-circle btn-sm"
                                                       href="/candidatures?agree=<?php echo $accepted['candidature_id']; ?>">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    <a class="btn btn-danger bg-danger btn-circle btn-sm"
                                                       href="/candidatures?disagree=<?php echo $accepted['candidature_id']; ?>">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                
                                            </td>
                                        </tr>
                                      
                                </table>

                            </div>
                        </div>
                            
                    </div>
                    <?php
                          } }
                   ?>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="swing"
                                    style="font-size: 18px;">Stages</h6>
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false"
                                            data-bs-toggle="dropdown" type="button"><i
                                                class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        <p class="text-center dropdown-header">plus de détails</p><a
                                                class="dropdown-item" href="/stages">&nbsp;afficher tous les stages</a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="piechartStage"></div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="swing"
                                    style="font-size: 18px;">Offres</h6>
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false"
                                            data-bs-toggle="dropdown" type="button"><i
                                                class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        <p class="text-center dropdown-header">plus de détails</p><a
                                                class="dropdown-item" href="/offres">&nbsp;afficher tous les Offres</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="piechartOffre"></div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="swing"
                                    style="font-size: 18px;">Candidatures</h6>
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false"
                                            data-bs-toggle="dropdown" type="button"><i
                                                class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        <p class="text-center dropdown-header">plus de détails</p><a
                                                class="dropdown-item" href="#">&nbsp;afficher tous les candidatures</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="piechartCandidature"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto mb-4">
                        <div class="card textwhite bg-primary text-white shadow" style="background: rgb(253,253,253);">
                            <div class="card-header py-3">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="shake"
                                    style="font-size: 18px;">En Chiffre</h6>
                            </div>
                            <div class="card-body" style="background: var(--bs-body-bg);">
                                <div class="row align-items-center bounce animated no-gutters"
                                     style="padding-bottom: 38px;border-style: solid;border-color: #7380ec;border-top-style: none;border-right-style: none;border-bottom-style: none;border-bottom-color: rgb(115,128,236);border-left-style: none;">
                                    <div class="col me-2">
                                        <div class="text-uppercase text-success rubberBand animated fw-bold text-xs mb-1">
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Entreprises</span>
                                        </div>
                                        <div class="text-dark fw-bold h5 mb-0"><span class="flash animated">
                                                     <?php //nbr ENTREPRISE
                                                     try {
                                                         $staff = $pdo->prepare("SELECT count(*) FROM entreprise");
                                                         $staff->execute();
                                                         $staffrow = $staff->fetch(PDO::FETCH_NUM);
                                                         $staffcount = $staffrow[0];


                                                         echo $staffcount;


                                                     } catch (Exception $e) {
                                                         $error = $e->getMessage();
                                                     }
                                                     ?>
                                            </span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-university fa-2x text-gray-300"
                                                             data-bss-hover-animate="swing"></i></div>
                                </div>
                                <div class="row align-items-center no-gutters"
                                     style="padding-bottom: 38px;border-style: solid;border-right-style: none;border-right-color: rgb(115, 128, 236);border-bottom-style: none;border-left-style: none;border-left-color: rgb(115, 128, 236);">
                                    <div class="col me-2">
                                        <div class="text-uppercase text-success rubberBand animated fw-bold text-xs mb-1">
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Offre En cours</span>
                                        </div>
                                        <div class="text-dark flash animated fw-bold h5 mb-0">
                                            <span>
                                            <?php //nbr offres
                                                     try {
                                                         $staff = $pdo->prepare("SELECT count(*) FROM offre o WHERE o.delai_offre > cast(now() as datetime) and o.formation_id= :formation_id");
                                                         $staff->bindParam(':formation_id', $curr_user['formation_id']);
                                                         $staff->execute();
                                                         $staffrow = $staff->fetch(PDO::FETCH_NUM);
                                                         $staffcount = $staffrow[0];


                                                         echo $staffcount;


                                                     } catch (Exception $e) {
                                                         $error = $e->getMessage();
                                                     }
                                                     ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-hourglass-end fa-2x text-gray-300"
                                                             data-bss-hover-animate="swing"></i></div>
                                </div>
                                <div class="row align-items-center no-gutters"
                                     style="padding-bottom: 38px;background: var(--bs-body-bg);border-style: solid;border-color: #7380ec;border-top-style: none;border-top-color: rgb(115, 128, 236);border-right-style: none;border-bottom-style: none;border-bottom-color: rgb(115,128,236);border-left-style: none;">
                                    <div class="col me-2">
                                        <div class="text-uppercase text-success rubberBand animated fw-bold text-xs mb-1">
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Stages</span>
                                        </div>
                                        <div class="text-dark flash animated fw-bold h5 mb-0"><span><?php //nbr stages
                                                     try {
                                                         $staff = $pdo->prepare("SELECT count(*) FROM stage");
                                                         $staff->execute();
                                                         $staffrow = $staff->fetch(PDO::FETCH_NUM);
                                                         $staffcount = $staffrow[0];


                                                         echo $staffcount;


                                                     } catch (Exception $e) {
                                                         $error = $e->getMessage();
                                                     }
                                                     ?>
                                               </span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-suitcase fa-2x text-gray-300"
                                                             data-bss-hover-animate="swing"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card textwhite bg-primary text-white shadow" style="background: rgb(253,253,253);">

                            <div class="card-body" style="background: var(--bs-body-bg);">

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="text-primary m-0 fw-bold">Nouvelles Offres ajoutées</h6>
                                    </div>
                                    <div class="card-body" style="background: rgba(133,135,150,0.29);">
                                        <!-- Todo ::   add recently added students -->
                                        <?php

                                        if (empty($recent_offres)) {
                                            echo "aucune offre";
                                        } else
                                            foreach ($recent_offres as $rOffre) {
                                                ?>
                                                <!--Element-->
                                                <div class="row mb-3">
                                                    <div class="col text-center">
                                                    <img class="border img-fluid" style="max-block-size: 100px"
                                                             src="/uploads?logo_id=<?php echo $rOffre['entId']; ?>">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="card-subtitle">
                                                            <?php echo "Titre: {$rOffre['title']}" ?>
                                                        </div>
                                                        <small class="text-muted"><?php echo "Delai:{$rOffre['delai_offre']} --- Nombre stragiaire:{$rOffre['nbr_stagiaire']} " ?></small>

                                                    </div>
                                                </div>
                                                <hr>
                                                <?php
                                            }

                                        ?>
                                    </div>
                                </div>

                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="text-primary m-0 fw-bold">Nouvelles Entreprises ajoutées</h6>
                                    </div>
                                    <div class="card-body" style="background: rgba(133,135,150,0.29);">
                                        <!-- Todo ::   add recently added companies -->
                                        <?php

                                        if (empty($recent_companies)) {
                                            echo "aucun Entreprise";
                                        } else
                                            foreach ($recent_companies as $rComp) {
                                                ?>
                                                <!--Element-->
                                                <div class="row mb-3 ">
                                                    <div class="col text-center">
                                                        <img class="border img-fluid" style="max-block-size: 100px"
                                                             src="/uploads?logo_id=<?php echo $rComp['id']; ?>">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="card-subtitle">
                                                            <?php echo "{$rComp['name']} {$rComp['web_site']}" ?>
                                                        </div>
                                                        <small class="text-muted"><?php echo "{$rComp['email']} --- {$rComp['domaine']} " ?></small>

                                                    </div>
                                                </div>
                                                <hr>
                                                <?php
                                            }

                                        ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <footer class="bg-white sticky-footer">
                    <div class="container my-auto">
                        <div class="text-center my-auto copyright">
                            <span>Copyright © Gestion de stage 2022</span></div>
                    </div>
                </footer>
            </div>

            <a class="border rounded d-inline scroll-to-top" href="#page-top"><i
                        class="fas fa-angle-up"></i></a>
        </div>
       

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/js/chart.min.js"></script>
        <script src="/assets/js/bs-init.js"></script>
        <script src="/assets/js/theme.js"></script>
      
</body>

</html>