<?php
$curr_user = $_SESSION['user'];
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
if (empty($_GET['id']))
    header('Location: /entrepries');
$formation_id = $_GET['id'];

try {
    $query = "SELECT f.*, p.fname, p.lname, p.cin 
                FROM formation f, person p 
                WHERE f.id = :id AND f.responsable_id = p.id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $formation_id);
    $stmt->execute();
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($formation)) {
        $error = "Formation `$formation_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    die($e->getMessage());
    header('Location: /formations');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['responsable_id']) &&
        !empty($_POST['short_title']) &&
        !empty($_POST['title']) &&
        !empty($_POST['type'])) {


        $short_title = $_POST['short_title'];
        $title = $_POST['title'];
        $type = $_POST['type'];
        $responsable_id = $_POST['responsable_id'];

        $edits_mode = ($responsable_id != $formation['responsable_id']) ?
            (($short_title !== $formation['short_title']) ?
                1
                :
                2)
            :
            ($short_title !== $formation['short_title'] ? 3 : 0);

        if ($edits_mode > 0) {
            if ($edits_mode === 1)
                $query = "SELECT * FROM formation where responsable_id = :id_resp OR short_title like :short_title";
            elseif ($edits_mode === 2)
                $query = "SELECT * FROM formation where responsable_id = :id_resp";
            elseif ($edits_mode === 3)
                $query = "SELECT * FROM formation where short_title like :short_title";
            $stmt = $pdo->prepare($query);
            if ($edits_mode < 3)
                $stmt->bindParam(':id_resp', $responsable_id);
            if ($edits_mode != 2)
                $stmt->bindParam(':short_title', $short_title);
            $stmt->execute();

            $formation_validate = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($formation_validate)) {
                if ($formation['responsable_id'] == $responsable_id) {
                    $error = "Enseignant `{$formation_validate['responsable_id']}` 
                    est deja responsable du fillier `{$formation_validate['short_title']}`";
                    goto skip_process;
                } elseif (!strcasecmp($formation_validate['short_title'], $short_title)) {
                    $error = "Formation avec Nom court `{$formation_validate['short_title']}` est deja cree.";
                    goto skip_process;
                }
            }
        }


        try {
            $pdo->beginTransaction();

            $query = "UPDATE formation SET short_title = :short_title, title = :title,
                        type = :type, responsable_id = :responsable_id WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':short_title', $short_title);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':responsable_id', $responsable_id);
            $stmt->bindParam(':id', $formation_id);

            if ($stmt->execute()) {
                $msg = "formation $short_title est modifiee";

                $query = "SELECT f.*, p.fname, p.lname, p.cin 
                            FROM formation f, person p 
                            WHERE f.id = :id AND f.responsable_id = p.id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id', $formation_id);
                $stmt->execute();
                $formation = $stmt->fetch(PDO::FETCH_ASSOC);

                $pdo->commit();
            } else {
                $error = "formation n'est pas Modifiee";
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
    <title>Gestion des Formation</title>
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
                    <h3 class="text-dark mb-0">Detailles de la formation `<?php echo $formation_id ?>`</h3>
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
                        <p class="text-primary m-0 fw-bold">Formations</p>
                    </div>
                    <div class="card-body">

                        <form class="d-flex flex-column flex-fill justify-content-around align-content-start"
                              style="font-size: calc(0.5em + 1vmin);" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input class="form-control" type="text" required
                                       name="title" placeholder="Nom de la Filliere"
                                       maxlength="149" minlength="5" value="<?php echo $formation['title']; ?>"/>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Enseigant non responsable</label>
                                <select class="form-select flex-grow-1" required name="responsable_id">
                                    <option selected value="<?php echo $formation['responsable_id']; ?>">
                                        <?php echo strtoupper($formation['lname']) .
                                            " {$formation['fname']} : {$formation['cin']}" . ' (CURRENT)'; ?>
                                    </option>
                                    <?php
                                    try {
                                        $query = "SELECT p.id,p.fname,p.lname,p.cin 
                                            FROM enseignant e, person p 
                                            WHERE e.id = p.id 
                                            AND e.id NOT IN (SELECT DISTINCT responsable_id FROM formation) 
                                            ORDER BY p.lname, p.fname";

                                        $stmt = $pdo->prepare($query);
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if (!empty($rows)) {
                                            foreach ($rows as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['id']; ?>"
                                                    <?php if ($value['id'] === $formation['responsable_id'])
                                                        echo ' selected'; ?>
                                                >
                                                    <?php echo strtoupper($value['lname']) . " {$value['fname']} : {$value['cin']}"; ?>
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
                            <div class="row row-cols-2 mb-3">
                                <div class="col-auto flex-grow-1">
                                    <label class="form-label flex-grow-1">Nom Court</label>
                                    <input class="form-control" type="text" required minlength="2" maxlength="10"
                                           name="short_title" placeholder="ilisi, gmi, magbio..."
                                           value="<?php echo $formation['short_title']; ?>"/>
                                </div>
                                <div class="col-auto col-lg-auto flex-grow-1">
                                    <label class="form-label">Type de Fomation</label>
                                    <select class="form-select" required name="type">
                                        <option value="LST"
                                            <?php if ($formation['type'] === "LST")
                                                echo ' selected'; ?>
                                        >Licence des Sciences et Techniques
                                        </option>
                                        <option value="MSlT"
                                            <?php if ($formation['type'] === "MST")
                                                echo ' selected'; ?>
                                        >Master des Sciences et Techniques
                                        </option>
                                        <option value="LP"
                                            <?php if ($formation['type'] === "LP")
                                                echo ' selected'; ?>
                                        >Licence Profetionnelle
                                        </option>
                                        <option value="FI"
                                            <?php if ($formation['type'] === "FI")
                                                echo ' selected'; ?>
                                        >Fillier d&#39;ingenieur
                                        </option>
                                        <option value="MS"
                                            <?php if ($formation['type'] === "MS")
                                                echo ' selected'; ?>
                                        >Master Specialise
                                        </option>
                                    </select>
                                </div>
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



