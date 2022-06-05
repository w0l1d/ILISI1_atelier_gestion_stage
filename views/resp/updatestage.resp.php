<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id']))
    header('Location: /stages');

$stage_id = $_GET['id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(!empty($_POST['encadrant_id']) &&
    !empty($_POST['stagiaire_id']) &&
    !empty($_POST['entreprise_id']) &&
    !empty($_POST['statue']) &&
    !empty($_POST['start']) &&
    !empty($_POST['end']) &&
    !empty($_POST['description'])){
        


    $description = $_POST['description'];
    $stagiaire_id = $_POST['stagiaire_id'];
    $end_stage = $_POST['end'];
    $start_stage = $_POST['start'];
    $statue = $_POST['statue'];
    $encadrant_id = $_POST['encadrant_id'];
    $entreprise_id = $_POST['entreprise_id'];


        $query = "UPDATE stage set stagiaire_id=:stagiaire_id, `description` = :description,
                 end = :end_date, start =:start_stage ,encadrant_id = :encadrant_id, entreprise_id = :entreprise_id, 
                 statue = :statue,updated_date =cast(NOW() as datetime )  WHERE id = :id";
        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':id', $stage_id);
        $stmt->bindParam(':stagiaire_id', $stagiaire_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':statue', $statue);
        $stmt->bindParam(':end_date', $end_stage);
        $stmt->bindParam(':start_stage', $start_stage);
        $stmt->bindParam(':encadrant_id', $encadrant_id);
        $stmt->bindParam(':entreprise_id', $entreprise_id);
        

        if ($stmt->execute())
            $msg = "Le stage est bien Mise a jour";
        else
            $error = "Erreur : Maj n'est pas effectee";

    } else
        $error = "Veuillez entrer les champs obligatoires";
}
try {
    $query = "SELECT * FROM stage WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $stage_id);
    $stmt->execute();
    $stage = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($stage)) {
        $error = "Stage `$stage_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    header('Location: /offres');
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gestion des Stages</title>
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
                <?php require_once 'parts/navbar.html' ?>
                <div class="container-fluid">
                    <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0"><br></h3>

                    </div>

                    <?php
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
                    <?php } ?>

                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Modifier Stage</p>
                        </div>
                        <div class="card-body">
                            <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                                method="post" style="font-size: calc(0.5em + 1vmin);">
                                <div class="mb-3">
                                    <label class="form-label">stagiaire<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                                <select name="stagiaire_id" class="form-select flex-grow-1" required="">

                                    <?php
                                    try {
                                        $query = "SELECT e.id as etudiant, fname ,lname FROM etudiant e , person p 
                                        where p.id=e.id and e.formation_id=( 
                                                                    SELECT id FROM formation WHERE responsable_id=:resp
                                                                            )
                                        and e.id not in ( 
                                                        SELECT e.id FROM etudiant e , stage s 
                                                        where s.stagiaire_id=e.id and e.id !=:curent_id 
                                                        and cast(s.end as datetime ) >= cast(NOW() as datetime )
                                                        ) ";

                                        $stmt = $pdo->prepare($query);
                                        $stmt->bindParam(':resp', $curr_user['id']);
                                        $stmt->bindParam(':curent_id', $stage['stagiaire_id']);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if (!empty($rows)) {
                                            foreach ($rows as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['etudiant']; ?>"
                                                <?php if ($value['etudiant'] === $stage['stagiaire_id'])
                                                            echo ' selected';?>
                                                >   
                                                    <?php echo "{$value['etudiant']} :{$value['fname']} {$value['lname']}"; ?>
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
                                <div class="mb-3">
                                    <label class="form-label">Entreprise<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                    <select name="entreprise_id" class="form-select flex-grow-1" required="">

                                        <?php
                                        try {
                                            $query = "SELECT id, name, short_name FROM entreprise e";

                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            if (!empty($rows)) {
                                                foreach ($rows as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value['id']; ?>"
                                                        <?php if ($value['id'] === $stage['entreprise_id'])
                                                            echo ' selected';?>
                                                    >
                                                        <?php echo "{$value['short_name']}: {$value['name']}"; ?>
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
                                <div class="mb-3">
                                    <label class="form-label">Encadrant<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                    <select name="encadrant_id" class="form-select flex-grow-1" required="">

                                        <?php
                                        try {
                                            $query = "SELECT e.id, fname, lname FROM enseignant e ,person p
                                                        WHERE p.id = e.id";

                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            if (!empty($rows)) {
                                                foreach ($rows as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value['id']; ?>"
                                                        <?php if ($value['id'] === $stage['encadrant_id'])
                                                            echo ' selected';?>
                                                    >
                                                        <?php echo "{$value['id']}: {$value['fname']}"; ?>
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
                                <fieldset class="mb-3">
                                    <legend>Specification</legend>
                                
                                                                        
                                        <div class="col-auto col-lg-auto flex-grow-1  mb-2">
                                            <label class="form-label">Statue<span
                                                        style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                            <select class="form-select" required name="statue">
                                                <option value="DRAFT" <?php if ($stage['statue'] === "DRAFT"  )?>>planifie</option>
                                                <option value="IN_PROGRESS" <?php if ($stage['statue'] === "IN_PROGRESS"  )?>>en cours</option>
                                                <option value="CANCELLED" <?php if ($stage['statue'] === "CANCELLED"  )?>>Annule</option>
                                                <option value="FINISHED" <?php if ($stage['statue'] === "FINISHED"  )?>>termine</option>
                                            </select>
                                        </div>
                                    
                                    </div>
                                </fieldset>
                                <fieldset class="mb-3">
                                    <legend>Period de stage</legend>
                                    <div class="row">
                                        <div class="col mb-2">
                                            <label class="form-label">debut<span
                                                        style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                            <input class="form-control" type="date" name="start"
                                                required="" value="<?php echo $stage['start']; ?>">
                                        </div>
                                        <div class="col mb-2">
                                            <label class="form-label">fin<span
                                                        style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                            <input class="form-control" type="date" name="end"
                                                required="" value="<?php echo $stage['end']; ?>">
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="mb-3">
                                    <label class="form-label">Description<span
                                                style="color: var(--bs-red);font-weight: bold;">*</span></label>
                                    <textarea class="border rounded form-control"
                                            placeholder="Description" name="description"
                                            maxlength="254"><?php echo $stage['description']; ?></textarea>
                                </div>
                                <button class="btn btn-primary" type="submit">Modifier</button>
                        </form>
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
    </div>

    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/bs-init.js"></script>
    <script src="/assets/js/theme.js"></script>


</body>

</html>


