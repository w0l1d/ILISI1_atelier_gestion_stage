<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id'])) {
    header('Location: /stages');
}
$stage_id = $_GET['id'];


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
//  header('Location: /stages');
}
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
    $query = "SELECT d.id, d.titre, d.type, d.name 
                FROM document d, doc_categorie c
                 WHERE d.stage_id = :stage_id 
                   AND c.id = d.categorie_id AND c.student_visible is true";

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
                    <div class="card-header">
                        <h5 class="text-dark mb-0">Jury</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (empty($stage_jury)) {
                            echo "Aucun Jury";
                        } else
                            foreach ($stage_jury as $jury) {
                                ?>
                                <div class="row d-flex mb-3">
                                    <div class="col-auto col-2 text-center">
                                        <img class="border img-profile rounded-circle img-fluid"
                                             style="max-block-size: 100px"
                                             src="<?php
                                             if (!empty($jury['profile_img']))
                                                 echo "/uploads?profile_id={$jury['id']}";
                                             else
                                                 echo "/assets/img/avatars/default_profile.png";
                                             ?>">
                                    </div>
                                    <div class="col">
                                        <div class="card-subtitle">
                                            <?php
                                            $jury['lname'] = strtoupper($jury['lname']);
                                            echo "{$jury['lname']} {$jury['fname']}"; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo "{$jury['email']} --- {$jury['phone']} " ?>
                                        </small>
                                    </div>
                                    <div class="col d-flex justify-content-center align-items-center flex-column text-lg">
                                        <input class="note-jury form-control text-center form-control-sm w-auto bg-info text-white border-0"
                                               type="number" max="20" min="0"
                                               default-note="<?php echo $jury['note']; ?>"
                                               size="6" minlength="1"
                                               value="<?php echo $jury['note']; ?>">
                                    </div>
                                    <div class="col d-flex gap-1 justify-content-center align-items-center">
                                        <a class="btn btn-primary btn-sm btn-circle visually-hidden"
                                           href="/stages?update&id=<?php echo $stage_id . "&jury={$jury['id']}" ?>">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm btn-circle"><i class="fa fa-trash"></i></a>
                                    </div>

                                </div>

                            <?php } ?>
                    </div>
                </div>
                <div class="card shadow mb-3">
                    <div class="card-header">
                        <h5 class="text-dark mb-0">Document</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        if (empty($stage_docs)) {
                            echo "Aucun Document";
                        } else
                            foreach ($stage_docs as $doc) {
                                ?>
                                <div class="row d-flex mb-3">
                                    <div class="col-auto col-2 text-center">
                                        <img class="border img-profile rounded-circle img-fluid"
                                             style="max-block-size: 100px"
                                             src="<?php
                                             echo "/uploads?doc_id={$doc['id']}";
                                             ?>">
                                    </div>
                                    <div class="col">
                                        <div class="card-subtitle">
                                            <?php
                                            echo $doc['name']; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo $doc['categorie'] ?>
                                        </small>
                                    </div>
                                    <div class="col  d-flex justify-content-center align-items-center text-lg">
                                        <a class="btn btn-sm btn-warning"
                                           href="<?php echo "/uploads?id_doc={$doc['id']}"; ?>">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                    <div class="col">
                                        ...
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


<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/js/bs-init.js"></script>
<script src="/assets/js/theme.js"></script>

<script>
    $(document).ready(function () {
        $(".note-jury").each(function () {
            $(this).bind('blur', function (e) {
                if ($(this).attr('default-note') != $(this).val()) {
                    console.log("changed");
                    $(this).closest('')
                    $(this).data("previousValue", $(this).val());
                }

            });
        });
    });
</script>

</body>

</html>



