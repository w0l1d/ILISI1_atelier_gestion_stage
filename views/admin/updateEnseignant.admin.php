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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['cin']) &&
        !empty($_POST['date_naiss']) &&
        !empty($_POST['fname']) &&
        !empty($_POST['lname']) &&
        !empty($_POST['phone']) &&
        !empty($_POST['email'])) {

        
        $cin = $_POST['cin'];
        $date_naiss = $_POST['date_naiss'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
     

        try {
           

            $query = "UPDATE person SET date_naiss = :date_naiss , cin = :cin,
                        fname = :fname, lname = :lname, phone = :phone, email = :email
                         WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':date_naiss', $date_naiss);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':id', $enseignants_id);

            if ($stmt->execute()) {
                $msg = "information de enseignant  $enseignants_id est modifiee";

            } else {
                $error = "formation n'est pas Modifiee";
               
            }

        } catch (Exception $e) {
           
            $error = $e->getMessage();
        }
    } else
        $error = "Veuillez entrer les champs obligatoires";
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des enseignants</title>
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
    <?php require_once 'parts/sidebar.php' ?>

    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Detailles de l'enseignant `<?php echo $enseignants_id ?>`</h3>
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
                        <p class="text-primary m-0 fw-bold">Enseignant</p>
                    </div>
                    <div class="card-body">

                        <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                              style="font-size: calc(0.5em + 1vmin);" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input class="form-control" type="text" required
                                       name="lname" placeholder="Nom de Enseignant"
                                       maxlength="149" minlength="5" value="<?php echo $enseignant['lname']; ?>"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prenom</label>
                                <input class="form-control" type="text" required
                                       name="fname" placeholder="prenom de Enseignant"
                                       maxlength="149" minlength="5" value="<?php echo $enseignant['fname']; ?>"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">cin</label>
                                <input class="form-control" type="text" required
                                       name="cin" placeholder="cin"
                                       maxlength="149" minlength="5" value="<?php echo $enseignant['cin']; ?>"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">date naissance</label>
                                <input class="form-control" type="date" required=""
                                       name="date_naiss"  value="<?php echo $enseignant['date_naiss']; ?>"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Telephone</label>
                                <input class="form-control" type="tel" required
                                       name="phone" placeholder="phone"
                                       maxlength="149" minlength="5" value="<?php echo $enseignant['phone']; ?>"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" required
                                       name="email" placeholder="email"
                                       maxlength="149" minlength="5" value="<?php echo $enseignant['email']; ?>"/>
                            </div>


                            <button class="btn btn-primary" type="submit">Modifier</button>
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
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>

</body>

</html>



