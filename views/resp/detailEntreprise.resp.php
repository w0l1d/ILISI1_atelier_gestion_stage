<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id'])) {
    header('Location: /entreprises');
}
$entreprise_id = $_GET['id'];


try {
    $query = "SELECT e.* FROM  entreprise e WHERE e.id = :id ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $entreprise_id);
    $stmt->execute();
    $entreprise = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($entreprise)) {
        $error = "Offre `$entreprise_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    header('Location: /entreprises');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Offres</title>
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
    <div class="d-flex flex-column" id="content-wrapper" style="font-size: calc(0.5em + 1vmin);">
        <div id="content">
            <?php require_once 'parts/navbar.php' ?>

            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">information sur Entreprise n° <?php echo $entreprise_id ?> <br></h3>

                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <table class="table" style="font-size: calc(0.5em + 1vmin);">
                            <tbody style="width: 913.6px;">
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Id Entreprise
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['id']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Domaine
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['domaine']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Email
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['email']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                   Logo
                                </td>
                                <td class="text-center text-uppercase"> <?php if (!empty($entreprise['logo'])) { ?>
                                                    <img src="/entreprises/logo?id=<?php echo $entreprise['id'] ?>"
                                                         width="50px" height="50px"/>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary text-uppercase font-monospace fw-light"
                                                          bs-cut="1"><i>NULL</i></span>
                                                <?php } ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                   Nom Court
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['short_name']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Nom
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['name']; ?></td>
                            </tr>
                           
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Telephone
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['phone']; ?><br></td>
                            </tr>
                           
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    site Web
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['web_site']; ?><br></td>
                            </tr>
                           
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    DESCRIPTION
                                </td>
                                <td class="text-center text-uppercase"><?php echo $entreprise['description']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>

               
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
 
</body>

</html>



