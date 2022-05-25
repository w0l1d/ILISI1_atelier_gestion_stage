<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();


try {
    $query = "SELECT id, created_date, delai_offre, description, duree_stage, end_stage, 
       nbr_stagiaire, start_stage, statue, title, updated_date, entreprise_id,
       formation_id, type_stage FROM offre e, ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validation des etudiants</title>
</head>
<body>

<table>

    <thead>
    <th>ID</th>
    <th></th>
    <th></th>
    <th></th>
    </thead>
    <?php

    if (!empty($rows)) {
        foreach ($rows as $key => $value) {
            print_r($value);
            echo "<br><br>";

        }
    }

    ?>


</table>


</body>
</html>




