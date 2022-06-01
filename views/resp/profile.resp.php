<?php
$curr_user = $_SESSION['user'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['contact-form'])) {
        if (!empty($_POST['email']) &&
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
    }

}
skip_process:
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
    <?php require_once 'parts/sidebar.php' ?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'parts/navbar.html' ?>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Profile</h3>
                <div class="row row-cols-2">
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-body text-center shadow">
                                <img class="rounded-circle mb-3 mt-4"
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
                            <div class="card-body text-center shadow">
                                <img class="rounded-circle mb-3 mt-4"
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
                                <input class="form-control" type="password" id="old-pwd" name="old-pwd"
                                       placeholder="Ancien Mot de passe">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="password" id="new-pwd"
                                       placeholder="Nouveau mot de passe" name="new-pwd">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" type="password" id="rnew-pwd"
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
                                            <strong>Téléphone</strong>
                                        </label>
                                        <input class="form-control" type="tel" id="username"
                                               value="<?php echo $curr_user['phone']; ?>" name="phone">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label" for="email">
                                            <strong>Adresse Email</strong>
                                        </label>
                                        <input class="form-control" type="email"
                                               id="email" name="email"
                                               value="<?php echo $curr_user['email']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col mb-3 mb-sm-0">
                                    <input class="form-control form-control-user"
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
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-init.js"></script>
<script src="assets/js/theme.js"></script>
</body>

</html>