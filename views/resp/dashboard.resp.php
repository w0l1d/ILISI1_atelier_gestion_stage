<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

//closed offer
try {
    $query_or = "SELECT o.id as id_offre,o.title,o.statue,o.type_stage ,t.short_name , r.key,r.submited_at FROM offre o,offreresults r,entreprise t
     WHERE o.id=r.offre_id AND t.id=o.entreprise_id AND o.statue='CLOSED'
    AND o.formation_id = (SELECT id from formation WHERE responsable_id = :resp_id)";

    $stmt_or = $pdo->prepare($query_or);
    $stmt_or->bindParam(':resp_id', $curr_user['id']);
    $stmt_or->execute();
    $closed_offre = $stmt_or->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

//accepted offer
try {
    $query_accepted = "SELECT c.id as candidature_id,o.id as offre_id,o.end_stage,o.start_stage,
                 o.description, o.entreprise_id ,c.etudiant_id FROM candidature c ,offre o
                WHERE o.id=c.offre_id AND c.status='AGREED'
                AND o.formation_id = (SELECT id from formation WHERE responsable_id = :resp_id)
                AND c.id not in(SELECT s.candidature_id FROM stage s WHERE s.stagiaire_id =c.etudiant_id)
                ";
                

    $stmt_accepted = $pdo->prepare($query_accepted);
    $stmt_accepted->bindParam(':resp_id', $curr_user['id']);
    $stmt_accepted->execute();
    $accepted_offre = $stmt_accepted->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}


try {
    $query = "SELECT p.lname, p.fname, e.promotion, e.cne, e.IsValidated FROM etudiant e, person p 
                                     WHERE e.id = p.id AND e.formation_id = (SELECT id from formation WHERE responsable_id = :resp_id)
                                     ORDER BY e.id DESC limit 3";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':resp_id', $curr_user['id']);
    $stmt->execute();
    $recent_students = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

?>



<?php //stage

try {
    $query1 = "SELECT statue ,count(*) as number FROM stage s ,etudiant e
                WHERE s.stagiaire_id  = e.id AND e.formation_id =:id_formation  GROUP BY statue";

    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(':id_formation',$curr_user['formation_id']);
    $stmt1->execute();
} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php //offres
try {
    $query2 = "SELECT statue ,count(*) as number FROM offre o
                WHERE o.formation_id= :id_formation GROUP BY statue";

    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindParam(':id_formation',$curr_user['formation_id']);
    $stmt2->execute();

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php //candidatures
try {
    $query3 = "SELECT status ,count(*) as number FROM candidature c ,etudiant e
    WHERE c.etudiant_id  = e.id AND e.formation_id =:id_formation GROUP BY status";

    $stmt3 = $pdo->prepare($query3);
    $stmt3->bindParam(':id_formation',$curr_user['formation_id']);
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

            var data = google.visualization.arrayToDataTable([
                ['statue', 'number'],
                <?php
                $row = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($row)) {
                    foreach ($row as $key => $value2) {
                        echo "['" . $value2["statue"] . "'," . $value2["number"] . "],";
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
                <!--closed offre-->
                <div class="row">
                    <?php
                        if (!empty($closed_offre)) {
                         foreach ($closed_offre as $key => $Coffre){
                            if (is_null($Coffre['submited_at'])){?>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        
                        <div class="card shadow mb-4 ">
                            <div class="card-header d-flex justify-content-between align-items-center  bg-warning" >
                                <h6 class="text-primary fw-bold m-0 " data-bss-hover-animate="swing"
                                    style="font-size: 18px ">Offre Fermée</h6>
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-link btn-sm dropdown-toggle " aria-expanded="false"
                                            data-bs-toggle="dropdown" type="button"><i
                                                class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        <p class="text-center dropdown-header">plus de détails</p><a
                                                class="dropdown-item" href="/offres">&nbsp;afficher tous les offres
                                              </a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body"  >
                                <table class="table">
                                        <tr>
                                            <th> Id de l'offre</th>
                                            <td><?php echo strtoupper($Coffre['id_offre']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Titre</th>
                                            <td><?php echo strtoupper($Coffre['title']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Type de stage</th>
                                            <td><?php echo $Coffre['type_stage'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Societé </th>
                                            <td><?php echo $Coffre['short_name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>statue </th>
                                            <td><?php echo $Coffre['statue'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Action </th>
                                            <td>
                                            <a class="btn btn-secondary bg-success btn-circle btn-sm"
                                                   href="/offres/view?id=<?php echo $Coffre['id_offre']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-primary bg-primary btn-circle btn-sm"
                                                   href="/offres/update?id=<?php echo $Coffre['id_offre']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="btn btn-primary bg-danger  btn-sm  "
                                                 href="/offres/send?id=<?php echo $Coffre['id_offre']; ?>"><i class="fas fa-envelope"></i> Envoyer
                                                </a>
                                                
                                            </td>
                                        </tr>
                                      
                                </table>

                            </div>
                        </div>
                            
                    </div>
                    <?php
                          } }}
                   ?>
                </div>
                 <!--accepted offre-->
                 <div class="row">
                    <?php
                        if (!empty($accepted_offre)) {
                         foreach ($accepted_offre as $key => $Aoffre){?>
                    <div class="col-lg-5 col-xl-4 bounce animated">
                        
                        <div class="card shadow mb-4 ">
                            <div class="card-header d-flex justify-content-between align-items-center  bg-success" >
                                <h6 class="text-primary fw-bold m-0 " data-bss-hover-animate="swing"
                                    style="font-size: 18px ">Creer stage pour l'etudiant <?php echo $Aoffre['etudiant_id'] ?> </h6>
                                <div class="dropdown no-arrow">
                                    <button class="btn btn-link btn-sm dropdown-toggle " aria-expanded="false"
                                            data-bs-toggle="dropdown" type="button"><i
                                                class="fas fa-ellipsis-v text-gray-400"></i></button>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                        <p class="text-center dropdown-header">plus de détails</p><a
                                                class="dropdown-item" href="/offres">&nbsp;afficher tous les offres
                                              </a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body"  >
                            <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                                 action="/stages" method="post" style="font-size: calc(0.5em + 1vmin);">
                                 <div class="mb-3">
                        <label class="form-label">Encadrant<span
                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                        <select name="encadrant_id" class="form-select flex-grow-1" required="">

                            <?php
                            try {
                                $req = "SELECT p.id, p.lname, p.fname FROM person p,
                                    enseignant e WHERE p.id=e.id ";

                                $stmt = $pdo->prepare($req);
                                $stmt->execute();
                                $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($select)) {
                                    foreach ($select as $key => $data) {
                                        ?>
                                        <option value="<?php echo $data['id']; ?>">
                                            <?php echo "{$data['id']}: {$data['lname']} {$data['fname']}"; ?>
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

                            <div class="col mb-2">
                                <label hidden class="form-label">Etudiant</label>
                                <input class="form-control" type="text" name="stagiaire_id" hidden value="<?php echo $Aoffre['etudiant_id'] ?>" readonly>
                               
                            </div>

                            <div class="col mb-2">
                                <label hidden class="form-label">Entreprise</label>
                                <input class="form-control" type="text" name="entreprise_id" hidden value="<?php echo $Aoffre['entreprise_id'] ?>" readonly>
                               
                            </div>
                            <div class="col mb-2">
                                <label class="form-label">Debut</label>
                                <input class="form-control" type="text" name="start" value="<?php echo $Aoffre['start_stage'] ?>" readonly>
                              
                            </div>
                            <div class="col mb-2">
                                <label class="form-label">Fin</label>
                                <input class="form-control" type="text" name="end" value="<?php echo $Aoffre['end_stage'] ?>" readonly>
                              
                            </div>
                            <div class="col mb-2">
                                <label class="form-label">Candidature Id</label>
                                <input class="form-control" type="text" name="candidature_id" value="<?php echo $Aoffre['candidature_id'] ?>" readonly>
                              
                            </div>
                            <div class="col-auto col-lg-auto flex-grow-1  mb-2">
                                <label class="form-label">Statue<span
                                            style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                <select class="form-select" required="" name="statue">
                                    <option value="DRAFT" selected="">Planifie</option>
                                    <option value="FINISHED">Termine</option>
                                    <option value="IN_PROGRESS">en cours</option>
                                    <option value="CANCELED">Annule</option>
                                </select>
                            </div>

                            <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="border rounded form-control" 
                                  placeholder="Description" name="description"
                                  maxlength="254"><?php echo $Aoffre['description'] ?> </textarea>
                    </div>
                    <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>

                         </form>
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
                                                                                    WHERE e.formation_id=:formation 
                                                                                      AND e.IsValidated IS FALSE");
                                                    $staff->bindParam(':formation', $curr_user['formation_id']);
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
                                                    $staff = $pdo->prepare("SELECT count(*) FROM etudiant e where e.formation_id=:formation");
                                                    $staff->bindParam(':formation', $curr_user['formation_id']);
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
                                    <div class="card-body" >
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
                                                        <div   class="fs-5  text-dark m-0 fw-italic">
                                                            <?php echo "{$rStud['lname']} {$rStud['fname']}" ?>
                                                        </div>
                                                        <small class="text-muted"><?php echo "{$rStud['cne']} --- {$rStud['promotion']} " ?></small>

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
                                    <div class="card-body" >
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
                                                        <div class="fs-5  text-dark m-0 fw-italic">
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