<?php
check_user_connected:
if (($user = isAuthenticated()) != null) {
    header('Location: /');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ///TODO :: validate fields
    if (!empty($_POST['cin']) ||
        !empty($_POST['lname']) ||
        !empty($_POST['fname']) ||
        !empty($_POST['date-naiss']) ||
        !empty($_POST['email']) ||
        !empty($_POST['password']) ||
        !empty($_POST['phone']) ||
        !empty($_POST['formation']) ||
        !empty($_POST['cne']) ||
        !empty($_POST['promotion'])) {

        $cin = trim($_POST['cin']);
        $cne = trim($_POST['cne']);
        $lname = trim($_POST['lname']);
        $fname = trim($_POST['fname']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $formation = strtoupper(trim($_POST['formation']));
        $promotion = trim($_POST['promotion']);
        $date_naiss = $_POST['date-naiss'];


        require_once('../private/shared/DBConnection.php');
        $pdo = getDBConnection();

        try {

            $pdo->beginTransaction();

            $query = "SELECT id FROM formation WHERE short_title = :short_title";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':short_title', $formation);
            $stmt->execute();
            $formation_id = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($formation_id)) {
                $error = "formation n'existe pas";
                $pdo->rollBack();
                goto skip_process;
            }

            $query = "INSERT INTO person 
                    (id, fname,lname,cin,date_naiss,email,phone,password)
                    VALUES (null,:fn,:ln,:cin,:dt_ns,:email,:phone,:pwd)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':fn', $fname);
            $stmt->bindParam(':ln', $lname);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':dt_ns', $date_naiss);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':pwd', $password);
            $stmt->execute();

            $query = "INSERT INTO etudiant (id, formation_id, cne, promotion)
                    VALUES ((select id from person where cin = :cin),:formation,:cne,:promo)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':cne', $cne);
            $stmt->bindParam(':promo', $promotion);
            $stmt->bindParam(':formation', $formation_id['id']);
            $stmt->execute();

            $pdo->commit();
            $msg = 'compte est bien cree';
        } catch (Exception $e) {
            $pdo->rollback();
            $error = $e->getMessage();
        }
    } else
        $error = "all fields are required !!";
}
skip_process:
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Register</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
</head>

<body class="bg-gradient-primary">
<div class="container">
    <div class="card shadow-lg o-hidden border-0 my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-5 d-none d-lg-flex">
                    <div class="flex-grow-1 bg-register-image"
                         style="background: url('assets/img/uh2c_logo.jpg') round;background-size: contain;">

                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h4 class="text-dark mb-4">Cree Compte Etudiant</h4>
                        </div>
                        <form class="user" action="/register" method="post">
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
                            <div class="row mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input class="form-control form-control-user"
                                           type="text" id="exampleFirstName-1"
                                           placeholder="CIN" name="cin" required=""
                                           minlength="6">
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control form-control-user" type="text"
                                           id="exampleFirstName-2" placeholder="CNE" name="cne"
                                           required="" minlength="10">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-flex flex-column flex-grow-1 flex-fill">Date de naissance
                                    <input class="form-control form-control-user" id="exampleInputEmail-2"
                                                    aria-describedby="emailHelp" placeholder="Telephone"
                                                    name="date-naiss" required="" type="date">
                                </label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input class="form-control form-control-user"
                                           type="text" id="exampleFirstName-3"
                                           placeholder="Promotion : (annee d'inscription) "
                                           name="promotion" required=""
                                           pattern="^20[0-9]{2}$">
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control form-control-user" type="text"
                                           id="exampleFirstName-4"
                                           placeholder="Formation (Ex. ILISI, GMI...)"
                                           name="formation" required="" minlength="2" maxlength="15">
                                </div>
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
                            <div class="row mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input class="form-control form-control-user"
                                           type="password" id="examplePasswordInput-1"
                                           placeholder="Password" name="password"
                                           required="">
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control form-control-user" type="password"
                                           id="exampleRepeatPasswordInput-1"
                                           placeholder="Repeat Password" name="rpassword" required="">
                                </div>
                            </div>
                            <?php if (!empty($error)) { ?>
                                <div class="alert alert-danger border rounded-pill alert-dismissible" role="alert"
                                     style="font-size: 12px;">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    <span><strong>Error:</strong>&nbsp;<?php echo $error; ?></span></div>
                            <?php } elseif (!empty($msg)) { ?>
                                <div class="alert alert-success border rounded-pill alert-dismissible" role="alert"
                                     style="font-size: 12px;">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    <span><?php echo $msg; ?></span></div>
                            <?php } ?>
                            <button class="btn btn-primary d-block btn-user w-100" type="submit">Register Account
                            </button>
                            <hr>
                        </form>
                        <div class="text-center"><a class="small" href="#">Oublier Mot de passe?</a></div>
                        <div class="text-center"><a class="small" href="/login">Vous avez deja un compte? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/theme.js"></script>
</body>

</html>