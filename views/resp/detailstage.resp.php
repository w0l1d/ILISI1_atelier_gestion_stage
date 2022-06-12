<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id'])) {
    header('Location: /stages');
    exit();
}
$stage_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_doc']) &&
        !empty($_POST['title']) &&
        !empty($_POST['type']) &&
        !empty($_POST['categorie_id']) &&
        !empty($_FILES["doc"])) {


        $title = $_POST['title'];
        $type = $_POST['type'];
        $categorie_id = $_POST['categorie_id'];


//        $query = 'SELECT name FROM doc_categorie where id = :id';
//        $stmt = $pdo->prepare($query);
//        $stmt->bindParam(':id',$categorie_id);
//        $stmt->execute();

        require_once __DIR__ . '/../../private/shared/tools.functions.php';

        $filename = $_FILES["doc"]["name"];
        $now = new DateTime();

        $updoc = generateRandomString(10) . '-' . $filename;
        $tempname = $_FILES["doc"]["tmp_name"];
        $folder = __DIR__ . "/../../private/uploads/Docs/" . $updoc;
        if (!move_uploaded_file($tempname, $folder)) {
            $error = "Failed to upload image";
            goto skip_process;
        }

        try {
            $pdo->beginTransaction();
            $query = 'INSERT INTO document (id, path, titre, type, categorie_id, stage_id) 
                        VALUES (null, :updoc, :title, :type, :cat, :stage_id)';
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':updoc', $updoc);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':cat', $categorie_id);
            $stmt->bindParam(':stage_id', $stage_id);
            $stmt->execute();

            $pdo->commit();

        } catch (Exception $e) {
            $pdo->rollback();
            $error = $e->getMessage();
        }
    } else
        header("Location: /stages/view?id=$stage_id");

}

if (isset($_GET['update_jury']) && !empty($_GET['jury'])) {
    $jury_id = $_GET['jury'];
    $note = $_GET['note'] ?? '';
    $query = 'UPDATE note_jury SET note = :note where jury_id = :jury AND stage_id = :stage_id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':jury', $jury_id);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    header("Location: /stages/view?id=$stage_id");
    exit();
}
if (isset($_GET['delete_jury']) && !empty($_GET['jury'])) {
    $jury_id = $_GET['jury'];
    $query = 'DELETE FROM note_jury where jury_id = :jury AND stage_id = :stage_id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':jury', $jury_id);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    header("Location: /stages/view?id=$stage_id");
    exit();
}
if (isset($_GET['add_jury']) && !empty($_GET['jury_id'])) {
    $jury_id = $_GET['jury_id'];
    $note = empty($_GET['note']) ? null : $_GET['note'];
    $query = 'INSERT INTO note_jury (note, jury_id, stage_id) VALUES (:note, :jury, :stage_id)';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':note', $note);
    $stmt->bindParam(':jury', $jury_id);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    header("Location: /stages/view?id=$stage_id");
    exit();
}


try {
    $query = "SELECT   e.*,s.* , pe.* ,ps.*,e.id as entreprise_id , s.id as staage_id  ,
                ps.id as etud_id  ,pe.id as ens_id , s.statue as stage_statue, s.created_date as stage_created_date,
                ps.fname as etu_fname,ps.lname as etu_lname, pe.fname as ens_fname,pe.lname as ens_lname
                FROM  entreprise e,stage s,person ps,person pe
                WHERE s.id =:stage_id and s.stagiaire_id=ps.id and e.id=s.entreprise_id and pe.id=s.encadrant_id
                ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    $stage = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($stage)) {
        $error = "stage `$stage_id` n'est pas trouve";
        require_once(__DIR__ . '/../404.php');
        die();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
skip_process:
?>


<?php

try {
    $query = "SELECT p.id, email, fname, lname, phone, profile_img, j.note 
                FROM person p, note_jury j WHERE j.stage_id = :stage_id 
                                             AND j.jury_id = p.id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    $stage_jury = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<?php
try {
    $query = "SELECT d.id, d.titre, d.type, c.name
                FROM document d, doc_categorie c
                 WHERE d.stage_id = :stage_id 
                   AND c.id = d.categorie_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':stage_id', $stage_id);
    $stmt->execute();
    $stage_docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
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
    require_once __DIR__ . '/parts/sidebar.php'
    ?>
    <div class="d-flex flex-column" id="content-wrapper" style="font-size: calc(0.5em + 1vmin);">
        <div id="content">
            <?php require_once __DIR__ . '/parts/navbar.php' ?>

            <div class="container-fluid">
                <div class="d-flex d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">information sur le stage n° <?php echo $stage_id ?> <br></h3>
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
                <?php }?>

                <div class="card shadow mb-3">
                    <div class="card-header">
                        <h5 class="text-dark mb-0">stage <br></h5>
                    </div>
                    <div class="card-body">
                        <table class="table" style="font-size: calc(0.5em + 1vmin);">
                            <tbody style="width: 913.6px;">
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    numero de stage
                                </td>
                                <td class="text-center "><?php echo $stage['staage_id']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    statue du stage
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['stage_statue']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    stagiaire
                                </td>
                                <td class="text-center"><?php echo $stage['stagiaire_id'] . ":" . $stage['etu_fname'] . $stage['etu_lname']; ?></td>
                            </tr>


                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Entreprise
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['entreprise_id'] . ":" . $stage['short_name']; ?></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    duree de stage
                                </td>
                                <td class="text-center text-uppercase"><?php


                                    $date1 = new DateTime($stage['start']);
                                    $date2 = new DateTime($stage['end']);

                                    $diff = $date2->diff($date1);

                                    echo $diff->days . " days ";


                                    ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    debut
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['start']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    fin
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['end']; ?><br></td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    date de creation de le stage
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['stage_created_date']; ?><br>
                                </td>
                            </tr>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Encadrant
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['encadrant_id'] . ":" . $stage['ens_fname'] . $stage['ens_lname']; ?>
                                    <br></td>
                            </tr>
                            <?php if ($stage['statue'] === "FINISHED") { ?>
                                <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                    <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                        Note de l'Encadrant
                                    </td>
                                    <td class="text-center text-uppercase"><?php echo $stage['encardant_note']; ?><br>
                                    </td>
                                </tr>
                                <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                    <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                        Note de l'Encadrant Exterieur
                                    </td>
                                    <td class="text-center text-uppercase"><?php echo $stage['encadrant_ext_note']; ?>
                                        <br></td>
                                </tr>
                            <?php } ?>
                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                    Description
                                </td>
                                <td class="text-center text-uppercase"><?php echo $stage['description']; ?><br></td>
                            </tr>

                            <?php if (!empty($stage['candidature_id'])) { ?>
                                <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                    <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                        numero de candidature
                                    </td>
                                    <td class="text-center text-uppercase"><?php echo $stage['candidature_id']; ?><br>
                                    </td>
                                </tr>
                                <?php try {
                                    $query = "SELECT c.*,o.*,o.id as offre_id, c.id as candidature_id ,
                                            c.created_date as cand_created_date, o.created_date as offre_created_date,
                                            c.status as cand_statue, o.statue as off_statue
                                            FROM offre o, candidature c  where  c.id=:candidature AND o.id=c.offre_id";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->bindParam(':candidature', $stage['candidature_id']);
                                    $stmt->execute();
                                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    if (!empty($rows)) {
                                        foreach ($rows as $key => $value) {
                                            ?>

                                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                                    numero d'offre
                                                </td>
                                                <td class="text-center text-uppercase"><?php echo $value['offre_id']; ?>
                                                    <br></td>
                                            </tr>
                                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                                    date de creation de l'offre
                                                </td>
                                                <td class="text-center text-uppercase"><?php echo $value['offre_created_date']; ?>
                                                    <br></td>
                                            </tr>
                                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                                    date de creation de candidature
                                                </td>
                                                <td class="text-center text-uppercase"><?php echo $value['cand_created_date']; ?>
                                                    <br></td>
                                            </tr>
                                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                                    statue de candidature
                                                </td>
                                                <td class="text-center text-uppercase"><?php echo $value['cand_statue']; ?>
                                                    <br></td>
                                            </tr>
                                            <tr class="d-flex flex-column flex-grow-1" style="padding: -2px;">
                                                <td style="background: rgba(154,170,169,0.23);border-style: outset;border-color: var(--bs-gray);color: rgb(35,28,32);font-size: 17px;font-family: 'Abril Fatface', serif;">
                                                    statue de l'offre
                                                </td>
                                                <td class="text-center text-uppercase"><?php echo $value['off_statue']; ?>
                                                    <br></td>
                                            </tr>

                                            <?php
                                        }
                                    } else
                                        echo "Nothing found";
                                } catch (Exception $e) {
                                    echo 'Erreur : ' . $e->getMessage();
                                }
                            }
                            ?>


                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card shadow mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="text-dark mb-0">Jury</h5>
                        <button class="btn btn-primary" type="button" data-bs-target="#modal-jury"
                                data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i>
                            <span class="d-none d-sm-inline-block d-md-inline-block">Ajouter Jury</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <?php
                        if (empty($stage_jury)) {
                            echo "Aucun Jury";
                        } else
                            foreach ($stage_jury as $jury) {
                                ?>
                                <div id="jury-<?php echo $jury['id'] ?>"
                                     class="d-flex mb-3 justify-content-between g-3">
                                    <div class="text-center w-25 p-2">
                                        <img class="border img-profile rounded-circle img-fluid"
                                             style="max-block-size: 100px" src="<?php
                                        if (!empty($jury['profile_img'])) echo "/uploads?profile_id={$jury['id']}";
                                        else echo "/assets/img/avatars/default_profile.png";
                                        ?>">
                                    </div>
                                    <div class="d-flex flex-grow-1 gap-2 gap-lg-5 flex-fill justify-content-evenly flex-wrap">
                                        <div class="flex-fill">
                                            <div class="card-subtitle">
                                                <?php $jury['lname'] = strtoupper($jury['lname']);
                                                echo "{$jury['lname']} {$jury['fname']}"; ?>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo "{$jury['email']} --- {$jury['phone']} " ?>
                                            </small>
                                        </div>
                                        <div class="d-flex justify-content-center align-items-center flex-column text-lg">

                                            <form method="GET" id="update-jury-<?php echo $jury['id']; ?>">
                                                <input hidden name="update_jury">
                                                <input type="number" name="id" value="<?php echo $stage_id; ?>" hidden>
                                                <input type="number" name="jury" value="<?php echo $jury['id']; ?>"
                                                       hidden>
                                                <input class="note-jury form-control text-center form-control-sm w-auto bg-info text-white border-0"
                                                       type="number" max="20" min="0" size="6" minlength="1"
                                                       default-note="<?php echo $jury['note']; ?>"
                                                       name="note" step=".01"
                                                       value="<?php echo $jury['note']; ?>">
                                            </form>
                                        </div>
                                        <div class="d-flex gap-1 justify-content-center align-items-center">
                                            <button class="btn btn-primary btn-sm btn-circle visually-hidden update-button"
                                                    type="submit" form="update-jury-<?php echo $jury['id']; ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form method="get">
                                                <input hidden name="delete_jury">
                                                <input type="number" name="id" value="<?php echo $stage_id; ?>" hidden>
                                                <input type="number" name="jury" value="<?php echo $jury['id']; ?>"
                                                       hidden>
                                                <button type="submit" class="btn btn-danger btn-sm btn-circle">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                            <?php } ?>
                    </div>
                </div>
                <div class="card shadow mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="text-dark mb-0">Document</h5>
                        <button class="btn btn-primary" type="button" data-bs-target="#modal-doc"
                                data-bs-toggle="modal"><i class="fas fa-plus fa-sm text-white-50"></i>
                            <span class="d-none d-sm-inline-block d-md-inline-block">Ajouter Document</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <?php
                        if (empty($stage_docs)) {
                            echo "Aucun Document";
                        } else
                            foreach ($stage_docs as $doc) {
                                ?>
                                <div class="row d-flex mb-3">
                                    <div class="col">
                                        <div class="card-title fs-3">
                                            <?php
                                            echo $doc['titre']; ?>
                                        </div>
                                        <small class="badge bg-light text-black text-muted">
                                            <?php echo $doc['name'] ?>
                                        </small>
                                    </div>
                                    <div class="col  d-flex justify-content-center align-items-center text-lg">
                                        <a class="btn btn-warning"
                                           href="<?php echo "/uploads?id_doc={$doc['id']}"; ?>">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </div>

                            <?php } ?>
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


<!--ADD JURY MODAL-->
<form class="d-flex flex-column flex-fill justify-content-around align-content-start"
      method="get"
      style="font-size: calc(0.5em + 1vmin);">
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-jury">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter Jury</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input hidden name="add_jury">
                    <input type="number" name="id" value="<?php echo $stage_id; ?>" hidden>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <input class="note-jury form-control text-center form-control-sm w-auto bg-info text-white border-0"
                               type="number" max="20" min="0" size="6" minlength="1" id="new_note"
                               name="note" step=".01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jury<span
                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                        <select name="jury_id" class="form-select flex-grow-1" required="">

                            <?php
                            try {
                                $req = "SELECT p.id,p.cin, p.lname, p.fname FROM person p,
                                    enseignant e WHERE e.id not in (SELECT jury_id FROM note_jury 
                                                                                   WHERE stage_id = :stage_id) 
                                                   AND p.id=e.id ";

                                $stmt = $pdo->prepare($req);
                                $stmt->bindParam(':stage_id', $stage_id);
                                $stmt->execute();
                                $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($select)) {
                                    foreach ($select as $key => $data) {
                                        ?>
                                        <option value="<?php echo $data['id']; ?>">
                                            <?php echo "{$data['cin']}: {$data['lname']} {$data['fname']}"; ?>
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

                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!--ADD DOCUMENT MODAL-->
<form class="d-flex flex-column flex-fill justify-content-around align-content-start"
      method="post" enctype="multipart/form-data">
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-doc">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ajouter Document</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input hidden name="add_doc">

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input class="stage-doc form-control" name="title"
                               type="text" required minlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <input class="form-control" name="type"
                               type="text" required minlength="5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document</label>
                        <input class="stage-doc form-control" name="doc"
                               type="file" multiple required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categorie<span
                                    style="color: var(--bs-red);font-weight: bold;">*</span></label>
                        <select name="categorie_id" class="form-select flex-grow-1" required="">

                            <?php
                            try {
                                $req = "SELECT * FROM doc_categorie";

                                $stmt = $pdo->prepare($req);
                                $stmt->execute();
                                $select = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                if (!empty($select)) {
                                    foreach ($select as $key => $data) {
                                        ?>
                                        <option value="<?php echo $data['id']; ?>">
                                            <?php echo $data['name']; ?>
                                            <i class="fa fa-eye<?php if ($data['student_visible'])
                                                echo '-slash'; ?>"></i>
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

                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-primary" type="submit">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>

<script>
    $(document).ready(function () {
        $(".note-jury").each(function () {
            $(this).bind('blur keyup', function (e) {
                let vnote = $(this).val()
                var rnote = new RegExp('^([012]?[0-9](\.[0-9]{1,2})?)?$');

                if ((!rnote.test(vnote) || vnote < 0 || 20 < vnote)) {
                    $(this).val($(this).data("previousValue") ?? $(this).attr('default-note'));
                    console.log("hello");
                }
                else {
                    console.log("hello 222");
                    const jury = $(this).closest('div[id^="jury-"]');
                    if ($(this).attr('default-note') != $(this).val()) {
                        console.log("changed");
                        $('button[type="submit"]', jury).removeClass('visually-hidden');
                        $(this).data("previousValue", $(this).val());
                    } else {
                        console.log("hello 3333");
                        $('button.update-button', jury).addClass('visually-hidden');
                    }
                }
            });
        });

        $('#new_note').bind('blur keyup', function (e) {
            let vnote = $(this).val()
            var rnote = new RegExp('^([012]?[0-9](\.[0-9]{1,2})?)?$');

            if ((!rnote.test(vnote) || vnote < 0 || 20 < vnote))
                $(this).val($(this).data("previousValue"));
        });
    });
</script>


</body>

</html>



