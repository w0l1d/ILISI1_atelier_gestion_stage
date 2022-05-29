<?php

$curr_user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
</head>

<body id="page-top">
<div id="wrapper">
    <?php require_once 'sidebar.php' ?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'navbar.html' ?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Profile</h3>
                <div class="row row-cols-2">
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-body text-center shadow"><img class="rounded-circle mb-3 mt-4"
                                                                           src="assets/img/dogs/image2.jpeg" width="160"
                                                                           height="160">
                                <div class="mb-3">
                                    <button class="btn btn-primary btn-sm" type="button">Change CV</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-body text-center shadow"><img class="rounded-circle mb-3 mt-4"
                                                                           src="assets/img/dogs/image2.jpeg" width="160"
                                                                           height="160">
                                <div class="mb-3">
                                    <button class="btn btn-primary btn-sm" type="button">Change Photo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-3">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 fw-bold">Changer Mot de Passe</p>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3"><input class="form-control" type="password" id="address" name="address"
                                                     placeholder="Ancien Mot de passe"></div>
                            <div class="mb-3"><input class="form-control" type="password" id="address-2"
                                                     placeholder="Nouveau mot de passe" name="address"></div>
                            <div class="mb-3"><input class="form-control" type="password" id="address-1"
                                                     placeholder="Nouveau Mot de passe encore une fois" name="address">
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
                    <div class="card-body">
                        <form method="post" action="/profile/contact">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="first_name"><strong>Prénom</strong><br></label>
                                        <input class="form-control" type="text" id="first_name" name="first_name"
                                               value="<?php echo $curr_user['fname']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="last_name"><strong>Nom</strong></label>
                                        <input class="form-control" type="text" id="last_name" name="last_name"
                                               value="<?php echo $curr_user['lname']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3"><label class="form-label" for="username">
                                            <strong>Téléphone</strong><br>
                                        </label>
                                        <input class="form-control" type="tel" id="username"
                                               value="<?php echo $curr_user['phone']; ?>" name="phone">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="email"><strong>Adresse
                                                Email</strong></label>
                                        <input class="form-control" type="email"
                                               id="email" name="email"
                                               value="<?php echo $curr_user['email']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col mb-3 mb-sm-0"><input class="form-control form-control-user"
                                                                     type="password" id="examplePasswordInput-1"
                                                                     placeholder="Password" name="password"></div>
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
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/theme.js"></script>
</body>

</html>