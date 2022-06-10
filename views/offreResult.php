<?php
require_once(__DIR__ . '/../private/shared/DBConnection.php');
$pdo = getDBConnection();

if (!empty($_GET['key'])) {
    $key = $_GET['key'];

    try {
        $query = "SELECT o.*, r.* FROM offre o, offreresults r 
                    WHERE o.id = r.offre_id AND r.`key` like :rst_key;";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':rst_key', $key);
        $stmt->execute();
        $form = $stmt->fetch(PDO::FETCH_ASSOC);
        if (empty($form)) {
            $error = "Formulaire `$key` n'est pas trouve";
            require_once(__DIR__ . '/404.php');
            die();
        }
        if (!empty($form['submited_at'])) {
            $error = "la resultat du Formulaire `$key` est deja sauvegarde";
            require_once(__DIR__ . '/404.php');
            die();
        }
    } catch (Exception $e) {
        header('Location: /offres');
    }


} else {
    $error = "Formulaire n'est pas trouve";
    require_once(__DIR__ . '/404.php');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array();

    $retenu = json_decode($_POST['retenu']);
    $non_retenu = json_decode($_POST['non-retenu']);
    $waiting = json_decode($_POST['waiting']);

    if (count($retenu) > $form['nbr_stagiaire']) {
        $response['error'] = "Nombre de stagiaires doit etre inferieure ou egal au nombre demande " . $form['nbr_stagiaire'];
        goto respond_lbl;
    }


    try {
        $pdo->beginTransaction();

        $query = "UPDATE offreresults set submited_at = NOW() where `key` = :rst_key";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':rst_key', $key);
        $stmt->execute();

        $nbr_retenu = count($retenu);

        if ($nbr_retenu > 0) {

            $results = in_params($nbr_retenu);
            $in = join(", ", $results);

            $query = "UPDATE candidature SET status = 'ACCEPTED'
                   WHERE offre_id = :offre_id AND status NOT LIKE 'CANCELED' AND etudiant_id in ($in)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':offre_id', $form['offre_id']);
            for ($i = 0; $i < $nbr_retenu; $i++)
                $stmt->bindParam(':stud' . $i, $retenu[$i]);

            $stmt->execute();
        }

        $nbr_non_retenu = count($non_retenu);

        if ($nbr_non_retenu > 0) {

            $results = in_params($nbr_non_retenu);
            $in = join(", ", $results);

            $query = "UPDATE candidature SET status = 'NACCEPTED'
                   WHERE offre_id = :offre_id AND status NOT LIKE 'CANCELED' AND etudiant_id in ($in)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':offre_id', $form['offre_id']);
            for ($i = 0; $i < $nbr_non_retenu; $i++)
                $stmt->bindParam(':stud' . $i, $non_retenu[$i]);

            $stmt->execute();
        }

        $nbr_waiting = count($waiting);

        if ($nbr_waiting > 0) {

            for ($i = 1; $i <= $nbr_waiting; $i++) {
                $query = "UPDATE candidature SET status = 'WAITING', position = :etud_pos
                   WHERE offre_id = :offre_id AND status NOT LIKE 'CANCELED' AND etudiant_id = :etud_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':offre_id', $form['offre_id']);
                $stmt->bindParam(':etud_pos', $i);
                $stmt->bindParam(':etud_id', $waiting[$i-1]);
                $stmt->execute();
            }
        }
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollback();
        $response['error'] = $e->getMessage();
        goto respond_lbl;
    }


    respond_lbl:
    echo json_encode($response);
    exit();
}

function in_params($c)
{
    $result = array();
    for ($i = 0; $i < $c; $i++)
        array_push($result, ':stud' . $i);
    return $result;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>set offre result custom form</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/jquery/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>

<body>
<button class="btn btn-primary btn-lg position-fixed bottom-0 end-0 m-3 " style="z-index: 999" type="button"
        data-bs-target="#modal-1" data-bs-toggle="modal">
    <i class="fa fa-check"></i>
    Enregistrer
</button>
<div class="container">
    <div class="row">
        <div class="col" style="margin-bottom: 1.5rem;background: var(--bs-gray-700);color: var(--bs-white);">
            <h1 class="display-4">
                Resultat de l'Offre `<?php echo $form['id']; ?>`:
            </h1>
            <h5>Developpeur Web<br></h5>
        </div>
        <div class="w-100"></div>
        <div class="col" style="margin-bottom: 1rem;">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h4>Etudiant Non Retenue</h4>
                    <h6 class="text-muted mb-2">MAX = 3</h6>
                </div>
                <div class="card-body">
                    <ul id="naccepted_list"
                        class="list-unstyled sortable_list connectedSortable h-100">
                        <?php
                        try {
                            $query = "SELECT p.*, e.* FROM person p, etudiant e 
                                        WHERE e.id = p.id 
                                        AND e.id in (SELECT c.etudiant_id 
                                                     FROM candidature c 
                                                     WHERE c.offre_id = :offre_id)";

                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':offre_id', $form['id']);
                            $stmt->execute();
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($rows)) {
                                foreach ($rows as $key => $value) {
                                    ?>
                                    <li style="margin-bottom: 1rem;" id="student-<?php echo $value['id']; ?>">
                                        <div class="card d-flex flex-row" style="font-size: calc(0.4rem + 1vmin);">
                                            <div class="card-header d-flex flex-column justify-content-center">
                                                <img class="img-thumbnail img-fluid"
                                                     src="/uploads?profile_id=<?php echo $value['id']; ?>"
                                                     style="max-width: 100px;max-height: 100px;min-width: auto;min-height: auto;">
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <?php echo strtoupper($value['lname']) . ' ' . $value['fname']; ?>
                                                </h6>
                                                <h6 class="text-muted card-subtitle mb-2">
                                                </h6>
                                                <table class="table">
                                                    <tr>
                                                        <th> CIN</th>
                                                        <td><?php echo strtoupper($value['cin']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>CNE</th>
                                                        <td><?php echo strtoupper($value['cne']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fa fa-phone"></i></th>
                                                        <td><?php echo $value['phone'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fa fa-envelope"></i></th>
                                                        <td><?php echo $value['email'] ?></td>
                                                    </tr>

                                                </table>

                                            </div>
                                        </div>
                                    </li>

                                    <?php
                                }
                            } else
                                echo "Nothing found";
                        } catch (Exception $e) {
                            echo 'Erreur : ' . $e->getMessage();
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col" style="margin-bottom: 1rem;">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h4>Etudiant Retenue</h4>
                    <h6 class="text-muted mb-2">MAX : <?php echo $form['nbr_stagiaire']; ?></h6>
                </div>
                <div class="card-body">
                    <ul id="accepted_list" class="list-unstyled sortable_list connectedSortable h-100">
                    </ul>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="height: 100%;">
                <div class="card-header">
                    <h4>Liste d'Attente</h4>
                    <h6 class="text-muted mb-2">par ordre de merite<br></h6>
                </div>
                <div class="card-body">
                    <ol id="waiting_list" class="sortable_list connectedSortable h-100">
                    </ol>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Comfirmation</h4>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Voulez-vous vraiment enregistrer ?
                    <br>
                    vous pouvez pas modifier apres l'enregistrement .
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button"
                        data-bs-dismiss="modal">Fermer
                </button>
                <button class="btn btn-primary" id="save-btn"
                        type="button">Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/jquery/jquery-1.10.2.js"></script>
<script src="/assets/jquery/jquery-ui.js"></script>

<script>
    $(document).ready(function () {
        const MAX_RETENUE = <?php echo $form['nbr_stagiaire']; ?>;

        $(".sortable_list").sortable({
            connectWith: ".connectedSortable",
            stop: function (event, ui) {
                const droppedOn = this;
                const draggedElem = ui.item[0];

                if (droppedOn.id !== 'accepted_list') {
                    if ($('#accepted_list').children().length > MAX_RETENUE) {
                        console.log('checked');
                        $(this).sortable('cancel');
                    }
                    // console.log(droppedOn)
                    // console.log(ui)
                    // console.log(draggedElem)
                    // console.log($('#accepted_list').children().length)
                }
            }
        }).disableSelection();

        $('#save-btn').click(function (event) {

            const waiting_list = $('ol#waiting_list li[id^="student"]').map(function () {
                return this.id.split('-')[1];
            }).get()
            const retenu_list = $('ul#accepted_list li[id^="student"]').map(function () {
                return this.id.split('-')[1];
            }).get()
            const non_retenu_list = $('ul#naccepted_list li[id^="student"]').map(function () {
                return this.id.split('-')[1];
            }).get()

            if (retenu_list.length > MAX_RETENUE) {
                alert("Nombre des stagiares retenues doit etre inferieure a " + MAX_RETENUE);
                return;
            }

            const data = {
                "retenu": JSON.stringify(retenu_list),
                "non-retenu": JSON.stringify(non_retenu_list),
                "waiting": JSON.stringify(waiting_list)
            };
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: data,
                dataType: 'json'
            }).done(function (data) {
                console.log(data);
            });
            alert("Does this alert appear first or second?");
        });
    });

</script>


</body>


</html>