<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

require_once(__DIR__ . '/../../private/shared/tools.functions.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['cin']) ||
        !empty($_POST['lname']) ||
        !empty($_POST['fname']) ||
        !empty($_POST['date-naiss']) ||
        !empty($_POST['email']) ||
        !empty($_POST['phone'])) {


        $cin = $_POST['cin'];
        $date_naiss = $_POST['date-naiss'];
        $email = trim($_POST['email']);
        $fname = trim($_POST['fname']);
        $lname = trim($_POST['lname']);
        $phone = trim($_POST['phone']);

        try {
            $pdo->beginTransaction();

            $query = "INSERT INTO person (id, cin, date_naiss, email, fname, lname, password, phone, person_type) 
                                        VALUES (null, :cin, :date_naiss, :email, :fname, :lname, :password, :phone, :person_type)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':date_naiss', $date_naiss);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':password', $cin);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindValue(':person_type', 'enseignant');
            $stmt->execute();


            $query = "INSERT INTO enseignant (id) VALUES ((SELECT id FROM person WHERE cin LIKE :cin LIMIT 1))";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':cin', $cin);
            $stmt->execute();
            $msg = "enseignant " . strtoupper($lname) . " $fname est Inseree";
            $pdo->commit();

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
    <title>Gestion des Enseignant</title>
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
                    <h3 class="text-dark mb-0">Enseignants</h3>
                    <button class="btn btn-primary d-none d-sm-block d-md-block"
                            type="button" data-bs-target="#modal-1" data-bs-toggle="modal">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        ajouter Enseignant
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
                        <p class="text-primary m-0 fw-bold">Enseignants</p>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped nowrap"
                               style="width:100%; font-size: calc(0.5em + 1vmin);">
                            <thead>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>CIN</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Date de Naissance</th>
                            <th class="all">Action</th>
                            </thead>
                            <?php
                            try {
                                $query = "SELECT p.* FROM enseignant e, person p WHERE e.id = p.id;";

                                $stmt = $pdo->prepare($query);
                                $stmt->execute();
                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($rows)) {
                                    foreach ($rows as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $value['id']; ?></td>
                                            <td><?php echo $value['lname']; ?></td>
                                            <td><?php echo $value['fname']; ?></td>
                                            <td><?php echo $value['cin']; ?></td>
                                            <td><?php echo $value['email']; ?></td>
                                            <td><?php echo $value['phone']; ?></td>
                                            <td><?php echo $value['date_naiss']; ?></td>
                                            <td>
                                                <a class="btn btn-primary bg-primary btn-circle btn-sm"
                                                   href="/enseignants/update?id=<?php echo $value['id']; ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="btn btn-primary bg-secondary btn-circle btn-sm"
                                                   href="/enseignants/view?id=<?php echo $value['id']; ?>">
                                                    <i class="fas fa-eye"></i>
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
      style="font-size: calc(0.5em + 1vmin);" method="post" enctype="multipart/form-data">
    <div id="modal-1" class="modal fade" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter une Enseignant</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-sm-6 mb-3 mb-sm-0">
                            <input class="form-control form-control-user"
                                   type="text" id="exampleFirstName"
                                   placeholder="Prenom" name="fname" required="">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control form-control-user" type="text"
                                   id="exampleFirstName" placeholder="Nom" name="lname"
                                   required="">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input class="form-control form-control-user"
                               type="text" id="exampleFirstName-1"
                               placeholder="CIN" name="cin" required=""
                               minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex flex-column flex-grow-1 flex-fill">Date de naissance
                            <input class="form-control form-control-user" id="exampleInputEmail-2"
                                   aria-describedby="emailHelp" placeholder="Telephone"
                                   name="date-naiss" required="" type="date">
                        </label>
                    </div>
                    <div class="mb-3">
                        <input class="form-control form-control-user" type="tel"
                               id="exampleInputEmail-1" aria-describedby="emailHelp"
                               placeholder="Telephone" name="phone" required="">
                    </div>
                    <div class="mb-3">
                        <input class="form-control form-control-user" type="email"
                               id="exampleInputEmail" aria-describedby="emailHelp"
                               placeholder="Email Address" name="email" required="">
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-light"
                            type="button" data-bs-dismiss="modal">
                        Fermer
                    </button>
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</form>

</body>

</html>



