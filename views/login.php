<?php

/// logout user
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) &&
        !empty($_POST['password']) &&
        !empty($_POST['user-type'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $user_type = $_POST['user-type'];

        require_once('../private/shared/DBConnection.php');
        $pdo = getDBConnection();


        switch ($user_type) {
            case 1:
                $user_type = "etudiant";
                $query = "SELECT p.*, e.* FROM person p, etudiant e
                WHERE p.email = :email AND p.password like :pwd AND e.id = p.id";
                break;
            case 2:
                $user_type = "enseignant";
                $query = "SELECT p.*, e.*, f.id as formation_id  
                            FROM person p, enseignant e, formation f
                             WHERE p.email = :email 
                             AND p.password = :pwd 
                             AND p.id  = f.responsable_id
                             AND e.id = p.id";
                break;
            case 3:
                $user_type = "admin";
                $query = "SELECT p.*, e.* FROM person p, admin e
                WHERE p.email = :email AND p.password = :pwd AND e.id = p.id";

                break;
        }

        try {

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pwd', $password);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            if (!empty($row)) {
                $user = $row;
                $user['type'] = $user_type;
                if (($user_type === "etudiant") && !$user['IsValidated'])
                    $error = "Veuillez contacter votre responsable pour valider votre compte";
                else {
                    $_SESSION['user'] = $user;
                    header('Location: /');
                    die();
                }
            } else
                $error = "email ou mot de passe incorrect";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else
        $error = "all fields are required !!";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="/assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/fonts/fontawesome5-overrides.min.css">
</head>

<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-12 col-xl-10">
            <div class="card shadow-lg o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-flex">
                            <div class="flex-grow-1 bg-login-image"
                                 style="background: url(&quot;assets/img/uh2c_logo.jpg&quot;) round;"></div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h4 class="text-dark mb-4">Bienvenue</h4>
                                </div>
                                <form class="user" method="post" action="/login">
                                    <div class="mb-3"><input class="form-control form-control-user" type="email"
                                                             id="exampleInputEmail-1" aria-describedby="emailHelp"
                                                             placeholder="Enter votre Email" name="email"></div>
                                    <div class="mb-3"><input class="form-control form-control-user" type="password"
                                                             id="exampleInputPassword" placeholder="Mot de passe"
                                                             name="password"></div>
                                    <div class="mb-3"><select class="form-select" name="user-type">
                                            <option value="1" selected="">Etudiant</option>
                                            <option value="3">Admin</option>
                                            <option value="2">Responsable</option>
                                        </select></div>
                                    <?php if (!empty($error)) { ?>
                                        <div class="alert alert-danger" role="alert" style="font-size: 12.5px;">
                                            <span><strong>Erreur :&nbsp;</strong>&nbsp;<?php echo $error; ?></span>
                                        </div>
                                    <?php } ?>
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox small">
                                            <div class="form-check"><input class="form-check-input custom-control-input"
                                                                           type="checkbox" id="formCheck-1"><label
                                                        class="form-check-label custom-control-label" for="formCheck-1">Remember
                                                    Me</label></div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Login</button>
                                    <hr>
                                    <hr>
                                </form>
                                <div class="text-center"><a class="small" href="#">Mot de passe
                                        Oublie?</a></div>
                                <div class="text-center"><a class="small" href="/register">Creer un Compte!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>
</body>

</html>





