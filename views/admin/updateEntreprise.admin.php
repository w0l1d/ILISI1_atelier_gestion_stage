<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
if (empty($_GET['id']))
    header('Location: /entrepries');
$entreprise_id = $_GET['id'];

try {
    $query = "SELECT * FROM entreprise WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $entreprise_id);
    $stmt->execute();
    $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($entreprise)) {
        $error = "Entreprise `$entreprise_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    header('Location: /entreprises');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) &&
        !empty($_POST['domaine']) &&
        !empty($_POST['email'])) {
        
        $name = $_POST['name'];
        $domaine = $_POST['domaine'];
        $email = $_POST['email'];
        $short_name = $_POST['short_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $web_site = $_POST['web_site'] ?? '';
        $description = $_POST['description'] ?? '';
        $logo = '';
        if (!empty($_FILES["logo"])) {
            $filename = $_FILES["logo"]["name"];
            $logo = $entreprise['logo'];
            $tempname = $_FILES["logo"]["tmp_name"];
            $folder = __DIR__ . "/../../private/uploads/images/logo/" . $logo;
            if (!move_uploaded_file($tempname, $folder)) {
                $error = "Failed to upload image";
                goto skip_process;
            }
        }

        try {
            $pdo->beginTransaction();

            $query = "UPDATE entreprise SET domaine = :domaine, email = :email,
                      logo = :logo, short_name = :short_name, `name` = :name,
                      phone = :phone, web_site = :web_site, 
                      `description` = :description WHERE id = :id" ;
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $entreprise_id);
            $stmt->bindParam(':domaine', $domaine);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':short_name', $short_name);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':web_site', $web_site);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':logo', $logo);

            if ($stmt->execute()) {
                $msg = "entreprise $short_name est modifiee";

                $entreprise['domaine'] = $domaine;
                $entreprise['email'] = $email;
                $entreprise['short_name'] = $short_name;
                $entreprise['name'] = $name;
                $entreprise['phone'] = $phone;
                $entreprise['web_site'] = $web_site;
                $entreprise['description'] = $description;

                $pdo->commit();
            }else {
                $error = "entreprise n'est pas Modifiee";
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
    <title>Gestion des Entreprise</title>
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
                    <h3 class="text-dark mb-0">Entreprises</h3>

                    <button class="btn btn-primary d-none d-sm-block d-md-block"
                            type="button" data-bs-target="#modal-1" data-bs-toggle="modal">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        ajouter Entreprise
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
                        <p class="text-primary m-0 fw-bold">Entreprises</p>
                    </div>
                    <div class="card-body">

                        <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                              style="font-size: calc(0.5em + 1vmin);" method="post" enctype="multipart/form-data">
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Nom <span style="color: var(--bs-red);font-weight: bold;">*</span>
                                </label>
                                <input class="form-control" type="text" name="name"
                                       placeholder="Nom de l&#39;entreprise" required
                                       maxlength="100" value="<?php echo $entreprise['name'];?>"/></div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Nom Court</label>
                                <input class="form-control" type="text" name="short_name"
                                       placeholder="Nom Court de l&#39;entreprise" required
                                       maxlength="15" value="<?php echo $entreprise['short_name'];?>" /></div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Domaine <span style="color: var(--bs-red);font-weight: bold;">*</span>
                                </label>
                                <input class="form-control" type="text" name="domaine"
                                       placeholder="Domaine de l&#39;entreprise" required
                                       maxlength="50" value="<?php echo $entreprise['domaine'];?>"/>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Email <span style="color: var(--bs-red);font-weight: bold;">*</span>
                                </label>
                                <input class="form-control" type="text" name="email"
                                       placeholder="Email de l&#39;entreprise" required
                                       maxlength="80" value="<?php echo $entreprise['email'];?>"/>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Telephone</label>
                                <input class="form-control" type="text" name="phone"
                                       placeholder="Telephone de l&#39;entreprise"
                                       maxlength="15" value="<?php echo $entreprise['phone'];?>"/>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Site web</label>
                                <input class="form-control" type="text" name="web_site"
                                       placeholder="Site web de l&#39;entreprise"
                                       maxlength="250" value="<?php echo $entreprise['web_site'];?>"/>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Description</label>
                                <input class="form-control" type="text" name="description"
                                       placeholder="Description de l&#39;entreprise"
                                       maxlength="512" value="<?php echo $entreprise['description'];?>"/>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label class="form-label">
                                    Logo</label>
                                <input class="form-control" type="file" name="logo" id="logo"
                                       placeholder="Logo de l&#39;entreprise"
                                       accept="image/*" multiple/>
                                <?php if (!empty($entreprise['logo'])) { ?>
                                    <img src="/entreprises/logo?id=<?php echo $entreprise['id'] ?>"
                                         width="50px" height="50px"/>
                                <?php } else { ?>
                                    <span class="badge bg-secondary text-uppercase font-monospace fw-light"
                                          bs-cut="1"><i>NULL</i></span>
                                <?php } ?>
                            </div>

                        </form>
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
                    <h4 class="modal-title">Ajouter une Entreprise</h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Nom <span style="color: var(--bs-red);font-weight: bold;">*</span>
                        </label>
                        <input class="form-control" type="text" name="name"
                               placeholder="Nom de l&#39;entreprise" required maxlength="100"/></div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Nom Court</label>
                        <input class="form-control" type="text" name="short_name"
                               placeholder="Nom Court de l&#39;entreprise" maxlength="15" required/></div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Domaine <span style="color: var(--bs-red);font-weight: bold;">*</span>
                        </label>
                        <input class="form-control" type="text" name="domaine"
                               placeholder="Domaine de l&#39;entreprise" required maxlength="50"/>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Email <span style="color: var(--bs-red);font-weight: bold;">*</span>
                        </label>
                        <input class="form-control" type="text" name="email"
                               placeholder="Email de l&#39;entreprise" required maxlength="80"/>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Telephone</label>
                        <input class="form-control" type="text" name="phone"
                               placeholder="Telephone de l&#39;entreprise" maxlength="15"/>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Site web</label>
                        <input class="form-control" type="text" name="web_site"
                               placeholder="Site web de l&#39;entreprise" maxlength="250"/>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Description</label>
                        <input class="form-control" type="text" name="description"
                               placeholder="Description de l&#39;entreprise" maxlength="512"/>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label class="form-label">
                            Logo</label>
                        <input class="form-control" type="file" name="logo" id="logo"
                               placeholder="Logo de l&#39;entreprise"
                               accept="image/*" multiple/>
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



