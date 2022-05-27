<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.css"/>

    <script rel="stylesheet" src="assets/js/bootstrap.min.js"></script>

    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.12.1/datatables.min.js"></script>

    <link rel="stylesheet" href="/assets/css/Profil.css">
    <!--   <link rel="stylesheet" href="/css/bootstrap.min.css"> -->
    <!-- icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">

</head>
<body>
<div class="container">
    <vmenu>
        <div class="top">
            <div class="logo">
                <img src="/assets/pic/img.png">
                <h2>Stage<span class="primary">FSTM</span></h2>
            </div>
            <div class="close" id="close-btn">
                <span class="material-icons-sharp">close</span>
            </div>
        </div>
        <div class="sidebar">
            <a href="#">
                <span class="material-icons-sharp">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> person</span>
                <h3>Etudiants</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> work_history</span>
                <h3>Stages</h3>
            </a>

            <a href="#">
                <span class="material-icons-sharp">person_add</span>
                <h3>validation</h3>
                <span class="validation-count">6</span>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> business</span>
                <h3>Societes</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp"> post_add</span>
                <h3>Offres de Stage</h3>
            </a>
            <a href="#" class="active">
                <span class="material-icons-sharp"> edit</span>
                <h3>Modifier profil</h3>
            </a>


            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Deconnection</h3>
            </a>


        </div>
    </vmenu>


    <!-- end of vmenu-->
    <main>
        <div class="topSide">
            <h1>Modifier Profil</h1>
            <div class="top">
                <button id="menu-btn">
                    <span class="material-icons-sharp">menu</span>
                </button>
                <div class="theme-toggler">
                    <span class="material-icons-sharp active">light_mode</span>
                    <span class="material-icons-sharp ">dark_mode</span>
                </div>
                <div class="profile">
                    <div class="info">
                        <p> Hey, <?php echo "{$curr_user['lname']} {$curr_user['fname']}" ?></p>
                        <small class="text-muted"><?php echo $curr_user['type']; ?></small>
                    </div>
                    <div class="profile-photo">
                        <img src="/assets/pic/profile.jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class="container1">
            <table id="myTable">
                <thead>
                <th>id</th>
                <th>title</th>
                <th>nbr etuds</th>
                <th>description</th>
                <th>duree stage</th>
                <th>status</th>
                </thead>
                <?php
                try {
                    $query = "SELECT id, created_date, delai_offre, description, duree_stage, end_stage,
       nbr_stagiaire, start_stage, statue, title, updated_date, entreprise_id,
       formation_id, type_stage FROM offre e ";

                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                foreach ($rows as $key => $value) {
                    ?>
                    <tr>
                        <td><?php echo $value['id']; ?></td>
                        <td><?php echo $value['title']; ?></td>
                        <td><?php echo $value['type_stage']; ?></td>
                        <td><?php echo $value['nbr_stagiaire']; ?></td>
                        <td><?php echo $value['description']; ?></td>
                        <td><?php echo $value['duree_stage']; ?></td>
                        <td><?php echo $value['statue']; ?></td>
                    </tr>
                <?php
                }
                }
                } catch (Exception $e) {
                    echo 'Erreur : ' . $e->getMessage();
                } finally {
                ?>

                    <script>
                        $(document).ready(function () {
                            $('#myTable').DataTable({
                                responsive: true,
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.12.1/i18n/fr-FR.json"
                                }
                            });
                        });
                    </script>
                    <?php
                }
                ?>

            </table>
        </div>

    </main>
    <!--------------------End of main------------->


</div>

<script src="/assets/js/code.js"></script>
<!--<script src="jquery.js"></script>-->


<!-- <script src="/js/bootstrap.bundle.min.js"></script>-->
</body>
</html>



