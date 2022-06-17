<?php
 
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
if (empty($_GET['id']))
    header('Location: /enseignants');
$enseignants_id = $_GET['id'];
try {
    $query = "SELECT p.* FROM  person p 
                WHERE p.id = :id ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $enseignants_id);
    $stmt->execute();
    $enseignant = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($enseignant)) {
        $error = "enseignants `$enseignants_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    die($e->getMessage());
    header('Location: /enseignants');
}
    $query = "SELECT f.short_title FROM  formation f 
                WHERE f.responsable_id  = :id ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $enseignants_id);
    $stmt->execute();
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);
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
    require_once __DIR__ . '/parts/sidebar.php'
    ?>
    <div class="d-flex flex-column" id="content-wrapper" style="font-size: calc(0.5em + 1vmin);">
        <div id="content">
            <?php require_once __DIR__ . '/parts/navbar.php' ?>

            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">information sur l'Enseignant n° <?php echo $enseignants_id ?> <br></h3>
                    <a class="btn btn-primary"
                       href="/enseignants/update?id=<?php echo $enseignants_id; ?>">
                        <i class="fas fa-edit"></i>
                        <span class="d-none d-sm-inline-block">Modifier</span>
                    </a>

                </div>
               
 
               
                <div class="card shadow mb-3">
               
                                            <div class="card-header d-flex justify-content-center">
                                                <img class="rounded-circle z-depth-2"   
                                                     src="<?php if (!empty( $enseignant['profile_img']))echo " /uploads?profile_id={$enseignant['id']}
                                                    ";
                                                     else echo "/assets/img/avatars/default_profile.png";                                                  
                                        ?>"  style="max-width: 250px;max-height: 250px;min-width: auto;min-height: auto">
                                                      
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title d-flex  justify-content-center">
                                                    <?php echo strtoupper($enseignant['lname']) . ' ' . $enseignant['fname']; ?>
                                                </h6>
                                                <h6 class="text-muted card-subtitle mb-2">
                                                </h6>
                                                <table class="table">
                                                    <tr>
                                                        <th><i class="far fa-address-card"></i>  CIN</th>
                                                        <td><?php echo strtoupper($enseignant['cin']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="far fa-calendar"></i> Date de naissance</th>
                                                        <td> <?php echo strtoupper($enseignant['date_naiss']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fa fa-phone"> Telephone</i></th>
                                                        <td><?php echo $enseignant['phone'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fa fa-envelope"></i> Email</th>
                                                        <td><?php echo $enseignant['email'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="far fa-user"></i> respnsable du Filiere </th>
                                                        <td><?php   if (empty($formation)) {
                                                                    echo "-------------";}
                                                                    else {
                                                                        echo $formation['short_title'];
                                                                    }

                                                         ?></td>
                                                    </tr>

                                                </table>

                                            </div>
                                        </div>
             

            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © FSTM-STAGE 2022</span></div>
            </div>
        </footer>
    </div>
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
</div>





<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>

<script>
    $(document).ready(function () {
        $(".note-jury").each(function () {
            $(this).bind('blur keyup', function (e) {
                let vnote = $(this).val()
                var rnote = new RegExp('^([012]?[0-9](\.[0-9]{1,2})?)?$');

                if ((!rnote.test(vnote) || vnote < 0 || 20 < vnote)) {
                    $(this).val($(this).data("previousValue") ?? $(this).attr('default-note'));
                    console.log("hello");
                }
                else {
                    console.log("hello 222");
                    const jury = $(this).closest('div[id^="jury-"]');
                    if ($(this).attr('default-note') != $(this).val()) {
                        console.log("changed");
                        $('button[type="submit"]', jury).removeClass('visually-hidden');
                        $(this).data("previousValue", $(this).val());
                    } else {
                        console.log("hello 3333");
                        $('button.update-button', jury).addClass('visually-hidden');
                    }
                }
            });
        });

        $('#new_note').bind('blur keyup', function (e) {
            let vnote = $(this).val()
            var rnote = new RegExp('^([012]?[0-9](\.[0-9]{1,2})?)?$');

            if ((!rnote.test(vnote) || vnote < 0 || 20 < vnote))
                $(this).val($(this).data("previousValue"));
        });
    });
</script>


</body>

</html>



