<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
$note="0";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 


    if ( 
       
        !empty($_POST['encadrant_id']) &&
        !empty($_POST['stagiaire_id']) &&
        !empty($_POST['entreprise_id']) &&
        !empty($_POST['statue']) &&
        !empty($_POST['start']) &&
        !empty($_POST['end']) &&
        !empty($_POST['description'])) {
            

  
        $description = $_POST['description'];
        $id_stagiaire = $_POST['stagiaire_id'];
        $end_stage = $_POST['end'];
        $start_stage = $_POST['start'];
        $statue = $_POST['statue'];
        $id_encadrant = $_POST['encadrant_id'];
        $entreprise_id = $_POST['entreprise_id'];
  
       
        
        $query = "INSERT INTO stage 
                    (id, candidature_id, encadrant_id, stagiaire_id, entreprise_id, statue, start,
                    end, description, created_date,updated_date,encadrant_ext_note,encardant_note)
                    VALUES (null,null,:encadrant_id,:stagiaire_id,:entreprise_id,:statue,
                    :start,:end,:description,cast(NOW() as datetime ),cast(NOW() as datetime ),
                    :note,:note)";
        $stmt = $pdo->prepare($query);

       
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':encadrant_id', $id_encadrant);
        $stmt->bindParam(':stagiaire_id', $id_stagiaire);
        $stmt->bindParam(':entreprise_id', $entreprise_id);
       
        $stmt->bindParam(':statue', $statue);
        $stmt->bindParam(':start', $start_stage);
        $stmt->bindParam(':end', $end_stage);
        $stmt->bindParam(':note', $note);
         


        if ($stmt->execute())
            $msg = "Le stage est bien cree";
        else
            $error = "Erreur : stage n'est pas cree";

    } else
        $error = "Veuillez entrer les champs obligatoires";
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
                    <h3 class="text-dark mb-0">Liste Des stages<br></h3>
                    <button class="btn btn-primary d-none d-sm-block d-md-block" type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i>&nbsp;Ajouter Stage
                    </button>
                    <button class="btn btn-primary d-block d-sm-none d-md-none" type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i></button>
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
                        <p class="text-primary m-0 fw-bold">Stages</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin); ">
                            <thead>
                            <th>Id</th>
                            <th>Statue</th>
                            <th>stagiaire</th>
                            <th>Entreprise</th>
                            <th>Encadrant</th>
                            <th>Date Debut</th>
                            <th>Date Fin</th>
                            <th>Description</th>
                            <th>candidature Id </th>
                            <th> Note Encadrant </th>
                            <th> Note Encadrant Exterieur</th>
                            <th>Date de creation</th>
                            <th>Date de Maj</th>

                            <th class="all">Action</th>
                            <tr>
                           
                           
                            <th class="filterhead">Id</th>
                            <th class="filterhead">Statue</th>
                            <th class="filterhead">stagiaire</th>
                            <th class="filterhead">Entreprise</th>
                            <th class="filterhead">Encadrant</th>
                            <th class="filterhead">Date Debut</th>
                            <th class="filterhead">Date Fin</th>
                            <th class="filterhead">Description</th>
                            <th class="filterhead">candidature Id </th>
                            <th class="filterhead"> Note Encadrant </th>
                            <th class="filterhead"> Note Encadrant Exterieur</th>
                            <th class="filterhead">Date de creation</th>
                            <th class="filterhead">Date de Maj</th>

                            <th class="all"></th>
                                    
                                     
                                </tr>
                            </thead>
                            <?php
                            try {
                                                               
                                $query = "SELECT distinct s.*,s.id as stage_id,e.short_name,e.name,ps.fname as etu_fname,
                                            ps.lname as etu_lname, pe.fname as ens_fname,pe.lname as ens_lname
                                            FROM stage s, entreprise e, enseignant n,formation f, person ps ,person pe ,etudiant t
                                            WHERE s.entreprise_id=e.id and  n.id=f.responsable_id and ps.id =s.stagiaire_id
                                             and pe.id= s.encadrant_id and t.id=s.stagiaire_id and t.formation_id=f.id
                                             and f.id = (
                                                 select f.id from  formation f  where f.responsable_id= :id_ent
                                                 )
                                        ";

                                $stmt = $pdo->prepare($query);
                               $stmt->bindParam(':id_ent', $curr_user['id']);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['stage_id']; ?></td>
                                            <td><?php echo $value['statue']; ?></td>
                                            <td><?php echo $value['stagiaire_id'].":". $value['etu_fname']. $value['etu_lname']; ?></td>                 
                                            <td data-bs-toggle="tooltip" title="<?php echo $value['name']; ?>">
                                                <?php echo $value['short_name']; ?>
                                            </td>
                                            <td><?php echo $value['encadrant_id'].":". $value['ens_fname']. $value['ens_lname']; ?></td> 
                                            <td><?php echo $value['start']; ?></td>
                                            <td><?php echo $value['end']; ?></td>
                                            <td><?php echo $value['description']; ?></td>
                                            <td><?php echo $value['candidature_id']; ?></td>
                                            <td><?php if ( $value['statue'] === "FINISHED") echo $value['encardant_note'] ;
                                                        else echo " non disponible " ; ?></td>
                                            <td><?php if ( $value['statue'] === "FINISHED") echo $value['encadrant_ext_note'];
                                                        else echo " non disponible " ; ?></td>
                                            <td><?php echo $value['created_date']; ?></td>
                                            <td><?php echo $value['updated_date']; ?></td>
                                            <td>
                                                <a class="btn btn-secondary bg-secondary btn-circle btn-sm"
                                                   href="/stages/view?id=<?php echo $value['stage_id']; ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-primary bg-primary btn-circle btn-sm"
                                                   href="/stages/update?id=<?php echo $value['stage_id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="btn btn-primary bg-warning btn-circle btn-sm"
                                                   href="#">
                                                    <i class="fas fa-download"></i>
                                                </a>
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
      action="/stages" method="post" style="font-size: calc(0.5em + 1vmin);">
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter Stage</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                            <label class="form-label">Etudiant<span
                                        style="color: var(--bs-red);font-weight: bold;">*</span></label>
                            <select name="stagiaire_id" class="form-select flex-grow-1" required="">

                                <?php
                                try {
                                    $query = "SELECT e.id as etudiant, fname ,lname FROM etudiant e , person p 
                                    where p.id=e.id and e.formation_id=:formation and e.id not in ( SELECT e.id 
                                    FROM etudiant e , stage s  where s.stagiaire_id=e.id and e.formation_id=:formation
                                    and cast(s.end as datetime ) >= cast(NOW() as datetime )) ";

                                    $stmt = $pdo->prepare($query);
                                    $stmt->bindParam(':formation', $value['formation']);
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if (!empty($rows)) {
                                        foreach ($rows as $key => $value) {
                                            ?>
                                            <option value="<?php echo $value['etudiant']; ?>">
                                                <?php echo "{$value['etudiant']} :{$value['fname']} {$value['lname']}"; ?>
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
                                                <?php echo "{$value['id']} {$value['short_name']}: {$value['name']}"; ?>
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
                        <fieldset class="mb-3">
                            <legend>Specification</legend>
                            <div class="row row-cols-2">
                                                        
                                <div class="col-auto col-lg-auto flex-grow-1  mb-2">
                                    <label class="form-label">Statue<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                    <select class="form-select" required ="" name="statue">
                                        <option value="DRAFT" selected="">Planifie</option>
                                        <option value="FINISHED">Termine</option>
                                        <option value="IN_PROGRESS">en cours</option>
                                        <option value="CANCELED">Annule</option>
                                    </select>
                                </div>
                            
                            </div>
                        </fieldset>
                        <fieldset class="mb-3">
                            <legend>Period de stage</legend>
                            <div class="row">
                                <div class="col mb-2">
                                    <label class="form-label">debut<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                    <input class="form-control" type="date" name="start" required="">
                                </div>
                                <div class="col mb-2">
                                    <label class="form-label">fin<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                    <input class="form-control" type="date" name="end" required="">
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
<script src="https://nightly.datatables.net/scroller/js/dataTables.scroller.js?_=cd11977a9e85e84b9a1ebeb03f7b1a10"></script>
<script>
    $(document).ready(function() {

function hideSearchInputs(columns) {
  for (i=0; i<columns.length; i++) {
    if (columns[i]) {
      $('.filterhead:eq(' + i + ')' ).show();
    } else {
      $('.filterhead:eq(' + i + ')' ).hide();
    }
  }
}

var table = $('#myTable').DataTable({
    orderCellsTop: true,
    
  responsive: {
          details: {
              display: $.fn.dataTable.Responsive.display.modal( {
                  header: function ( row ) {
                      var data = row.data();
                      return 'Details for '+data[0]+' '+data[1];
                  }
              } ),
              renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table'
                    })
          }
  },
      scrollY:        200,
      scrollCollapse: false,
      scroller:       false,
   
      initComplete: function () {
        var api = this.api();
          $('.filterhead', api.table().header()).each( function (i) {
            var column = api.column(i);
              var select = $('<select><option value=""></option></select>')
                  .appendTo( $(this).empty() )
                  .on( 'change', function () {
                      var val = $.fn.dataTable.util.escapeRegex(
                          $(this).val()
                      );

                      column
                          .search( val ? '^'+val+'$' : '', true, false )
                          .draw();
                  } );

              column.data().unique().sort().each( function ( d, j ) {
                  select.append( '<option value="'+d+'">'+d+'</option>' );
              } );
          } );
        hideSearchInputs(api.columns().responsiveHidden().toArray());
      }
  } );

  table.on( 'responsive-resize', function ( e, datatable, columns ) {
      hideSearchInputs( columns );
 
  } );

});



</script>

</body>

</html>



