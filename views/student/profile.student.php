<?php
$curr_user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contact-form'])) {
        if (!empty($_POST['email']) &&
            !empty($_POST['password']) &&
            !empty($_POST['phone'])) {

            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];

            require_once(__DIR__ . '/../../private/shared/DBConnection.php');
            $pdo = getDBConnection();

            if ($curr_user['password'] !== $password) {
                $error = "Mot de passe incorrect";
                goto skip_process;
            }

            try {
                $query = "UPDATE person set email = :email, phone = :phone where id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id', $curr_user['id']);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':email', $email);
                if ($stmt->execute()) {
                    $msg = "Maj est bien effectee";
                    $curr_user['phone'] = $phone;
                    $curr_user['email'] = $email;

                } else {
                    $error = "Maj n'est pas effectee";
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        } else {
            $error = (empty($_POST['email']) ? 'email' : 'Telephone') . 'est obligatoire';
        }
    } elseif (isset($_POST['password-form'])) {
        if (!empty($_POST['old-pwd']) &&
            !empty($_POST['new-pwd']) &&
            !empty($_POST['rnew-pwd'])) {
            $old_pwd = $_POST['old-pwd'];
            $new_pwd = $_POST['new-pwd'];
            if ($curr_user['password'] !== $old_pwd) {
                $error = "Mot de passe incorrect";
            } elseif ($new_pwd !== $_POST['rnew-pwd']) {
                $error = "Nouveau Mot de passe n'est pas le meme";
            } else {
                require_once(__DIR__ . '/../../private/shared/DBConnection.php');
                $pdo = getDBConnection();

                $query = "UPDATE person set password = :password where id = :id";
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(':id', $curr_user['id']);
                $stmt->bindParam(':password', $new_pwd);
                if ($stmt->execute()) {
                    $curr_user['password'] = $new_pwd;
                    $msg = "Maj est bien effectee";
                } else {
                    $error = "Maj n'est pas effectee";
                }
            }
        } else
            $error = "veuillez renseigner tous les champs";

    } elseif (isset($_POST['profile-img-form'])) {
        if (!empty($_FILES["profile_picture"])) {
            require_once __DIR__ . '/../../private/shared/tools.functions.php';

            $filename = $_FILES["profile_picture"]["name"];
            $now = new DateTime();

            $profile_img = !empty($curr_user['profile_img']) ?
                $curr_user['profile_img'] : (generateRandomString(10) . '-' . $filename);
            $tempname = $_FILES["profile_picture"]["tmp_name"];
            $folder = __DIR__ . "/../../private/uploads/images/profiles/" . $profile_img;
            if (!move_uploaded_file($tempname, $folder)) {
                $error = "Failed to upload image";
                goto skip_process;
            }
        } else {
            $error = 'choisissez une photo !!';
            goto skip_process;
        }

        require_once(__DIR__ . '/../../private/shared/DBConnection.php');
        $pdo = getDBConnection();
        try {
            $pdo->beginTransaction();

            $query = "UPDATE person SET profile_img = :img WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':img', $profile_img);
            $stmt->bindParam(':id', $curr_user['id']);

            if ($stmt->execute()) {
                $msg = "Photo de profile est changee";
                $curr_user['profile_img'] = $profile_img;
                $pdo->commit();
            } else {
                $error = "Erreur : Photo de profile n'est pas changee";
                $pdo->rollBack();
            }

        } catch (Exception $e) {
            $pdo->rollback();
            $error = $e->getMessage();
        }
    } elseif (isset($_POST['cv-form'])) {
        if (!empty($_FILES['cv'])) {
            require_once __DIR__ . '/../../private/shared/tools.functions.php';

            $filename = $_FILES['cv']['name'];
            $now = new DateTime();

            $cv_file = !empty($curr_user['cv']) ?
                $curr_user['cv'] : (generateRandomString(10) . '-' . $filename);
            $tempname = $_FILES['cv']['tmp_name'];
            $folder = __DIR__ . '/../../private/uploads/Docs/CVs/' . $cv_file;
            if (!move_uploaded_file($tempname, $folder)) {
                $error = "Failed to upload CV";
                goto skip_process;
            }
        } else {
            $error = 'selectionner votre CV !!';
            goto skip_process;
        }

        require_once(__DIR__ . '/../../private/shared/DBConnection.php');
        $pdo = getDBConnection();
        try {
            $pdo->beginTransaction();

            $query = "UPDATE etudiant SET cv = :cv WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':cv', $cv_file);
            $stmt->bindParam(':id', $curr_user['id']);

            if ($stmt->execute()) {
                $msg = "votre CV est changee";
                $curr_user['cv'] = $cv_file;
                $pdo->commit();
            } else {
                $error = "votre CV n'est pas changee";
                $pdo->rollBack();
            }

        } catch (Exception $e) {
            $pdo->rollback();
            $error = $e->getMessage();
        }
    }
    $_SESSION['user'] = $curr_user;
}
skip_process:
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
</head>

<body id="page-top">
<div id="wrapper">
    <?php require_once 'parts/sidebar.php' ?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Profile</h3>
                <div class="card mb-3">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <p class="text-primary fw-bold">CV</p>
                        <div>
                            <button class="btn btn-primary btn-sm" type="button"
                                    data-bs-toggle="modal" data-bs-target="#updateCV">
                                <i class="fa fa-edit"></i> <span class="d-none d-sm-inline">Change CV</span>
                            </button>

                            <?php
                            if (!empty($curr_user['cv'])) { ?>
                                <a class="btn btn-warning btn-sm" target="_blank"
                                   href="/uploads?id_cv=<?php echo $curr_user['id']; ?>">
                                    <i class="fa fa-download"></i> <span
                                            class="d-none d-sm-inline">Telecharger CV</span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="card-body text-center shadow">
                        <canvas id="the-canvas" class="img-fluid"></canvas>
                    </div>
                </div>

                <div class="card shadow mb-3 ">
                    <div class="card-header py-3 d-flex justify-content-between">
                        <p class="text-primary fw-bold">Photo de profile</p>
                    </div>
                    <div class="card-body text-center shadow">
                        <img width="160" height="160" class="img-fluid rounded-circle"
                             src="/uploads?profile_id=<?php echo $curr_user['id'] ?>">
                        <?php if (isset($_POST['profile-img-form'])) {
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
                            <?php }
                        } ?>
                        <div class="my-4">
                            <button class="btn btn-primary btn-sm" type="button"
                                    data-bs-toggle="modal" data-bs-target="#updateProfileImage">
                                Change Photo
                            </button>
                        </div>
                    </div>
                </div>


                <div class="card shadow mb-3">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Changer Mot de Passe</p>
                    </div>
                    <?php if (isset($_POST['password-form'])) {
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
                        <?php }
                    } ?>
                    <div class="card-body">
                        <form method="post">
                            <input name="password-form" class="visually-hidden">

                            <div class="mb-3">
                                <input class="form-control" type="password"
                                       id="old-pwd" name="old-pwd" required
                                       placeholder="Ancien Mot de passe">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="password" id="new-pwd" required
                                       placeholder="Nouveau mot de passe" name="new-pwd">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="password" id="rnew-pwd" required
                                       placeholder="Nouveau Mot de passe encore une fois" name="rnew-pwd">
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary btn-sm" type="submit">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow mb-3">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Modifier Mes Coordonnées&nbsp;</p>
                    </div>
                    <?php if (isset($_POST['contact-form'])) {
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
                        <?php }
                    } ?>
                    <div class="card-body">
                        <form method="post" action="/profile">
                            <input name="contact-form" class="visually-hidden">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="first_name">
                                            <strong>Prénom</strong><br>
                                        </label>
                                        <input class="form-control" type="text"
                                               id="first_name" name="first_name"
                                               value="<?php echo $curr_user['fname']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="last_name">
                                            <strong>Nom</strong>
                                        </label>
                                        <input class="form-control" type="text"
                                               id="last_name" name="last_name" readonly
                                               value="<?php echo $curr_user['lname']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3"><label class="form-label" for="username">
                                            <strong>Téléphone</strong>
                                        </label>
                                        <input class="form-control" type="tel" id="username" required
                                               value="<?php echo $curr_user['phone']; ?>" name="phone">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="email">
                                            <strong>Adresse Email</strong>
                                        </label>
                                        <input class="form-control" type="email"
                                               id="email" name="email" required
                                               value="<?php echo $curr_user['email']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col mb-3 mb-sm-0">
                                    <input class="form-control form-control-user" required
                                           type="password" id="examplePasswordInput-1"
                                           placeholder="Password" name="password">
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    Sauvegarder
                                </button>
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


<!-- UPDATE CV Modal -->
<form class="mb-3" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="updateCV" tabindex="-1" aria-labelledby="updateCVLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCVLabel">Changer votre CV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input name="cv-form" class="visually-hidden">
                    <label class="form-label" for="cv">CV</label>
                    <input class="form-control" type="file" name="cv"
                           id="cv" placeholder="CV" required
                           accept="application/pdf" multiple/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- UPDATE CV Modal -->


<!-- UPDATE PROFILE PICTURE Modal -->
<form class="mb-3" method="post" enctype="multipart/form-data">
    <div class="modal fade" id="updateProfileImage" tabindex="-1" aria-labelledby="updateProfileImageLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileImageLabel">Changer photo de profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input name="profile-img-form" class="visually-hidden">
                    <label class="form-label" for="profile_picture">Photo de profile</label>
                    <input class="form-control" type="file" name="profile_picture"
                           id="profile_picture" placeholder="Photo de profile"
                           accept="image/*" multiple required/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- UPDATE PROFILE PICTURE Modal -->

<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>
<script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
<script>
    // If absolute URL from the remote server is provided, configure the CORS
    // header on that server.
    var url = '/uploads?id_cv=<?php echo $curr_user['id'];?>';

    // Loaded via <script> tag, create shortcut to access PDF.js exports.
    var pdfjsLib = window['pdfjs-dist/build/pdf'];

    // The workerSrc property shall be specified.
    pdfjsLib.GlobalWorkerOptions.workerSrc = '/assets/pdf viewer/pdf.worker.js';

    // Asynchronous download of PDF
    var loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(function (pdf) {
        console.log('PDF loaded');

        // Fetch the first page
        var pageNumber = 1;
        pdf.getPage(pageNumber).then(function (page) {
            console.log('Page loaded');

            var scale = 1.5;
            var viewport = page.getViewport({scale: scale});

            // Prepare canvas using PDF page dimensions
            var canvas = document.getElementById('the-canvas');
            var context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render PDF page into canvas context
            var renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            var renderTask = page.render(renderContext);
            renderTask.promise.then(function () {
                console.log('Page rendered');
            });
        });
    }, function (reason) {
        // PDF loading error
        console.error(reason);
    });

</script>
</body>


</html>