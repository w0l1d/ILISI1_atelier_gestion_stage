<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['responsable_id']) &&
        !empty($_POST['short_title']) &&
        !empty($_POST['title']) &&
        !empty($_POST['type'])) {

        $short_title    =   $_POST['short_title'];
        $title          =   $_POST['title'];
        $type           =   $_POST['type'];
        $responsable_id =   $_POST['responsable_id'];

        $query = "SELECT * FROM formation where responsable_id = :id_resp OR short_title like :short_title";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_resp', $responsable_id);
        $stmt->bindParam(':short_title', $short_title);
        $stmt->execute();

        $formation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($formation)) {
            if ($formation['responsable_id'] == $responsable_id) {
                $error = "Enseignant `{$formation['responsable_id']}` est deja responsable du fillier `{$formation['short_title']}`";
                goto skip_process;
            } elseif (!strcasecmp($formation['short_title'],$short_title)) {
                $error = "Formation avec Nom court `{$formation['short_title']}` est deja cree.";
                goto skip_process;
            }
        }

        try {
            $pdo->beginTransaction();

            $query = "INSERT INTO formation (id, short_title, title, type, responsable_id) 
                        VALUES (null, :short_title, :title, :type, :responsable_id)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':short_title', $short_title);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':responsable_id', $responsable_id);

            if ($stmt->execute()) {
                $msg = "Formation $short_title est Inseree";
                $pdo->commit();
            } else {
                $error = "Formation n'est pas Inseree";
                $pdo->rollBack();
            }

        } catch (Exception $e) {
            $pdo->rollback();
            $error = $e->getMessage();
        }
    } else
        $error = "Veuillez entrer les champs obligatoires";
}

skip_process:
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Formations</title>
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

    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Formations</h3>

                    <button class="btn btn-primary d-none d-sm-block d-md-block"
                            type="button" data-bs-target="#modal-1" data-bs-toggle="modal">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        ajouter Formation
                    </button>
                    <button class="btn btn-primary d-block d-sm-none d-md-none"
                            type="button" data-bs-target="#modal-1"
                            data-bs-toggle="modal" style="border-radius: 10px;">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                    </button>
                </div>

                <?php if (!empty($error)) {
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
                        <p class="text-primary m-0 fw-bold">Formations</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin);">
                            <thead>
                            <th>ID</th>
                            <th>Nom Court</th>
                            <th>Nom</th>
                            <th>Responsable</th>
                            <th>Type</th>
                            <th class="all">Action</th>
                            </thead>
                            <?php
                            try {
                                $query = "SELECT f.*, p.lname, p.fname FROM formation f, person p 
                                            WHERE f.responsable_id = p.id";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['id']; ?></td>
                                            <td><?php echo $value['short_title']; ?></td>
                                            <td><?php echo $value['title']; ?></td>
                                            <td>
                                                <a href="/enseignants/view?id=<?php echo $value['responsable_id']; ?>">
                                                    <?php echo strtoupper($value['lname']) . ' ' . $value['fname']; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $value['type']   ; ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-circle btn-sm"
                                                   href="/formations/update?id=<?php echo $value['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
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


<form class="d-flex flex-column flex-fill justify-content-around align-content-start"
      method="post" action="/formations">
    <div id="modal-1" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter une Formation</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div>
                        <label class="form-label">Nom</label>
                        <input class="form-control" type="text" required
                               name="title" placeholder="Nom de la Filliere"
                               maxlength="149" minlength="5"/>
                    </div>
                    <div>
                        <label class="form-label">Responsable</label>
                        <select class="form-select flex-grow-1" required name="responsable_id">
                            <?php
                            try {
                                $query = "SELECT p.id,p.fname,p.lname,p.cin 
                                            FROM enseignant e, person p 
                                            where e.id = p.id order by p.lname, p.fname";

                                $stmt = $pdo->prepare($query);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <option value="<?php echo $value['id']; ?>">
                                            <?php echo strtoupper($value['lname']). " {$value['fname']} : {$value['cin']}"; ?>
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
                    <div class="row row-cols-2">
                        <div class="col-auto flex-grow-1">
                            <label class="form-label flex-grow-1">Nom Court</label>
                            <input class="form-control" type="text" required minlength="2" maxlength="10"
                                   name="short_title" placeholder="ilisi, gmi, magbio..."/>
                        </div>
                        <div class="col-auto col-lg-auto flex-grow-1">
                            <label class="form-label">Type de stage</label>
                            <select class="form-select" required name="type">
                                <option value="LST" selected>Licence des Sciences et Techniques</option>
                                <option value="MST">Master des Sciences et Techniques</option>
                                <option value="LP">Licence Profetionnelle</option>
                                <option value="FI">Fillier d&#39;ingenieur</option>
                                <option value="MS">Master Specialise</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light"
                            type="button" data-bs-dismiss="modal">Fermer
                    </button>
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</form>

</body>

</html>



