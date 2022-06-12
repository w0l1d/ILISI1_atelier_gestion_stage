<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
//recents students
try {
    $query = "SELECT p.lname, p.fname, e.promotion, e.cne, e.IsValidated ,f.short_title FROM etudiant e, person p,formation f 
                                     WHERE e.id = p.id AND e.formation_id = f.id
                                     ORDER BY e.id DESC limit 4";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $recent_students = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}
//recent entreprises

try {
    $query_ent = "SELECT e.name, e.short_name,e.email,e.logo,e.domaine,e.web_site, e.id  FROM entreprise e 
                                   
                                     ORDER BY e.id DESC limit 3";

    $stmt_ent = $pdo->prepare($query_ent);

    $stmt_ent->execute();
    $recent_companies = $stmt_ent->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>



<?php // nombre de stage de chaque formation

try {
    $query1 = "SELECT f.short_title as short_title,count(*) as number FROM stage s, formation f
                WHERE s.encadrant_id = f.responsable_id
                GROUP BY f.id";

    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php //nombre d'offres de chaque formation
try {
    $query2 = "SELECT f.short_title as short_title,count(*) as number FROM offre o, formation f 
                WHERE o.formation_id =f.id GROUP BY f.id";

    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php //nombre de candidature de chaque formation
try {
    $query3 = "SELECT f.short_title as short_title  ,count(*) as number FROM candidature c,etudiant e,
                formation f WHERE c.etudiant_id =e.id AND  e.formation_id =f.id
                GROUP BY f.id";

    $stmt3 = $pdo->prepare($query3);
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
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic(){
            var data = google.visualization.arrayToDataTable([
                ['short_title', 'nombre'],
                <?php
                $row = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value1) {
                        echo "['" . $value1["short_title"] . "'," . $value1["number"] . "],";
                    }
                }
                ?>
            ]);
            var options = {
        title: 'Formation',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total stages',
          minValue: 0
        },
        vAxis: {
          title: 'formation'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('piechartStage'));

      chart.draw(data, options);
    }
    
      


    </script>
    <script type="text/javascript">
   google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic(){
            var data = google.visualization.arrayToDataTable([
                ['short_title', 'nombre'],
                <?php
                $row = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value2) {
                        echo "['" . $value2["short_title"] . "'," . $value2["number"] . "],";
                    }
                
                }
                ?>
            ]);
            var options = {
        title: 'Formation',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total offres',
          minValue: 0
        },
        vAxis: {
          title: 'formation'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('piechartOffre'));

      chart.draw(data, options);
    }
    
      
 
      
       


    </script>
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);

    function drawBasic(){
            var data = google.visualization.arrayToDataTable([
                ['short_title', 'Nombre'],
                <?php
                $row = $stmt3->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value3) {
                        echo "['" . $value3["short_title"] . "'," . $value3["number"] . "],";
                    }
                }
                ?>
            ]);
            var options = {
        title: 'Formation',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Total candidature',
          minValue: 0
        },
        vAxis: {
          title: 'formation'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('piechartCandidature'));

      chart.draw(data, options);
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
                <div class="row">
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="swing"
                                    style="font-size: 18px;">Stages</h6>
                                
                            </div>
                            <div class="card-body" id="piechartStage"></div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="swing"
                                    style="font-size: 18px;">Offres</h6>
                               
                            </div>
                            <div class="card-body" id="piechartOffre"></div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="text-primary fw-bold m-0" data-bss-hover-animate="swing"
                                    style="font-size: 18px;">Candidatures</h6>
                               
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
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Entreprise</span>
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
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Demande de validation</span>
                                        </div>
                                        <div class="text-dark flash animated fw-bold h5 mb-0">
                                            <span>
                                                <?php //nbr validation
                                                try {
                                                    $staff = $pdo->prepare("SELECT count(*) FROM etudiant e 
                                                                                    WHERE  e.IsValidated IS FALSE");
                                                
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
                                    <div class="col-auto"><i class="fas fa-user-check fa-2x text-gray-300"
                                                             data-bss-hover-animate="swing"></i></div>
                                </div>
                                <div class="row align-items-center no-gutters"
                                     style="padding-bottom: 38px;background: var(--bs-body-bg);border-style: solid;border-color: #7380ec;border-top-style: none;border-top-color: rgb(115, 128, 236);border-right-style: none;border-bottom-style: none;border-bottom-color: rgb(115,128,236);border-left-style: none;">
                                    <div class="col me-2">
                                        <div class="text-uppercase text-success rubberBand animated fw-bold text-xs mb-1">
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Etudiants</span>
                                        </div>
                                        <div class="text-dark flash animated fw-bold h5 mb-0"><span><?php //nbr etudiants
                                                try {
                                                    $staff = $pdo->prepare("SELECT count(*) FROM etudiant e ");
                                                    
                                                    $staff->execute();
                                                    $staffrow = $staff->fetch(PDO::FETCH_NUM);
                                                    $staffcount = $staffrow[0];


                                                    echo $staffcount;


                                                } catch (Exception $e) {
                                                    $error = $e->getMessage();
                                                }
                                                ?>  </span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-gray-300"
                                                             data-bss-hover-animate="swing"></i></div>
                                </div>
                                <div class="row align-items-center no-gutters"
                                     style="padding-bottom: 38px;background: var(--bs-body-bg);border-style: solid;border-color: #7380ec;border-top-style: none;border-top-color: rgb(115, 128, 236);border-right-style: none;border-bottom-style: none;border-bottom-color: rgb(115,128,236);border-left-style: none;">
                                    <div class="col me-2">
                                        <div class="text-uppercase text-success rubberBand animated fw-bold text-xs mb-1">
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Filiers</span>
                                        </div>
                                        <div class="text-dark flash animated fw-bold h5 mb-0"><span><?php //nbr filieres
                                                try {
                                                    $staff = $pdo->prepare("SELECT count(*) FROM formation  ");
                                                    
                                                    $staff->execute();
                                                    $staffrow = $staff->fetch(PDO::FETCH_NUM);
                                                    $staffcount = $staffrow[0];


                                                    echo $staffcount;


                                                } catch (Exception $e) {
                                                    $error = $e->getMessage();
                                                }
                                                ?>  </span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-code-branch fa-2x text-gray-300"
                                                             data-bss-hover-animate="swing"></i></div>
                                </div>
                                <div class="row align-items-center no-gutters"
                                     style="padding-bottom: 38px;background: var(--bs-body-bg);border-style: solid;border-color: #7380ec;border-top-style: none;border-top-color: rgb(115, 128, 236);border-right-style: none;border-bottom-style: none;border-bottom-color: rgb(115,128,236);border-left-style: none;">
                                    <div class="col me-2">
                                        <div class="text-uppercase text-success rubberBand animated fw-bold text-xs mb-1">
                                            <span style="color: rgba(157,179,130,0.79);font-size: 14.2px;font-weight: bold;font-style: italic;text-align: justify;">Enseignant</span>
                                        </div>
                                        <div class="text-dark flash animated fw-bold h5 mb-0"><span><?php //nbr etudiants
                                                try {
                                                    $staff = $pdo->prepare("SELECT count(*) FROM enseignant e ");
                                                    
                                                    $staff->execute();
                                                    $staffrow = $staff->fetch(PDO::FETCH_NUM);
                                                    $staffcount = $staffrow[0];


                                                    echo $staffcount;


                                                } catch (Exception $e) {
                                                    $error = $e->getMessage();
                                                }
                                                ?>  </span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-chalkboard-teacher fa-2x text-gray-300"
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
                                        <h6 class="text-primary m-0 fw-bold">Nouveaux Etudiants Ajoutes</h6>
                                    </div>
                                    <div class="card-body" style="background: rgba(133,135,150,0.29);">
                                        <!-- Todo ::   add recently added students -->
                                        <?php

                                        if (empty($recent_students)) {
                                            echo "aucun etudiant";
                                        } else
                                            foreach ($recent_students as $rStud) {
                                                ?>
                                                <!--Element-->
                                                <div class="row mb-3">
                                                    <div class="col text-center">
                                                        <img class="border img-profile rounded-circle img-fluid"
                                                             style="max-block-size: 100px"
                                                             src="<?php
                                                             if (!empty($rStud['profile_img']))
                                                                 echo "/uploads?profile_id={$rStud['id']}";
                                                             else
                                                                 echo "/assets/img/avatars/default_profile.png";
                                                             ?>">
                                                    </div>
                                                    <div class="col-9">
                                                        <div class="card-subtitle">
                                                            <?php echo "NOM: {$rStud['lname']} --- PRENOM:{$rStud['fname']}
                                                            ---FILIERE: {$rStud['short_title']}" ?>
                                                        </div>
                                                        <small class="text-muted"><?php echo " CNE:{$rStud['cne']} --- PROMOTION:{$rStud['promotion']} " ?></small>

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