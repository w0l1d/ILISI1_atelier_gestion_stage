<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$formation = $curr_user['formation_id'];
$moi="moi";
$autre="autre";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Entreprise</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/datatable/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/datatable/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
    <link href="https://nightly.datatables.net/scroller/css/scroller.dataTables.css?_=cd11977a9e85e84b9a1ebeb03f7b1a10.css" rel="stylesheet" type="text/css" />
</head>

<body id="page-top">
<div id="wrapper">
    <?php
    require_once 'parts/sidebar.php'
    ?>

    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'parts/navbar.html' ?>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">LISTE DES STAGES</h3>
                </div>
                <div class="card shadow">
                    <div class="card-header py-3">
                       
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin);">
                            <thead>
                          
                                <tr>
                
                            <th>ID</th>
                            <th>ENTREPRISE</th>
                            <th>STATUE</th>
                            <th>ENCADRANT</th>
                            <th>stagiaire</th>
                            <th>DATE DEBUT</th>
                            <th>DATE FIN</th>
                            <th>DATE DE CREATION</th>
                            <th>DATE DE MODIFICATION</th>
                            <th>CANDIDATURE ID</th>
                            <th>Description</th>
                            </tr>
                            <tr>
                            <th class="filterhead">ID</th>
                            <th class="filterhead">ENTREPRISE</th>
                            <th class="filterhead">STATUE</th>
                            <th class="filterhead">ENCADRANT</th>
                            <th class="filterhead" >stagiaire</th>
                            <th class="filterhead">DATE DEBUT</th>
                            <th class="filterhead">DATE FIN</th>
                            <th class="filterhead">DATE DE CREATION</th>
                            <th class="filterhead">DATE DE MODIFICATION</th>
                            
                            <th class="filterhead">CANDIDATURE ID</th>
                            <th class="filterhead">Description</th>
                                    
                                     
                                </tr>
                            </thead>
                            <?php
                            try {
                                 $query = "SELECT  s.* ,s.id as stage_id,t.short_name,ps.lname as lastname ,e.promotion as promotion FROM stage s ,etudiant e ,entreprise t, person ps
                                  WHERE e.formation_id =:formation and e.id = s.stagiaire_id AND t.id=s.entreprise_id AND ps.id=s.encadrant_id ";

                                $stmt = $pdo->prepare($query);
                                $stmt->bindParam(':formation', $formation);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['stage_id']; ?></td>
                                            <td><?php echo $value['short_name']; ?></td>
                                            <td><?php echo $value['statue']; ?></td>
                                           
                                            <td><?php echo $value['lastname']; ?></td>
                                            <td><?php 
                                            if ($value['stagiaire_id']==$curr_user['id'])
                                               {  
                                                   echo "moi meme" ;}
                                                else  {  
                                                    echo " Etudiant de la promotion ".$value['promotion']; ;};
                                            ?></td>
                                            <td><?php echo $value['start']; ?></td>
                                            <td><?php echo $value['end']; ?></td>
                                            <td><?php echo $value['created_date']; ?></td>
                                            <td><?php echo $value['updated_date']; ?></td>
                                            
                                            <td><?php echo $value['candidature_id'] ; ?></td>
                                            <td><?php echo $value['description']; ?></td>
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
      dom: 'Bfrtip',
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
      scrollCollapse: true,
      scroller:       true,
  buttons: [
          'copy', 'csv', 'excel', 'pdf', 'print'
      ],
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