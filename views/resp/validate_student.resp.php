<?php
require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();
if (!empty($_GET['validate'])) {
    try {

        $student_id = $_GET['validate'];
        $pdo->beginTransaction();

        $query = "UPDATE etudiant SET IsValidated = true where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $student_id);
        $stmt->execute();

        $pdo->commit();
        $msg = 'etudiant est valide';
    } catch (Exception $e) {
        $pdo->rollback();
        $error = $e->getMessage();
    }
}

try {
    $query = "SELECT id as etud_id, cin, date_naiss, email, fname, lname, phone,cne, promotion,
       formation_id, short_title, title, type FROM person p, etudiant e, formation f 
                WHERE e.IsValidated IS false AND p.id = e.id AND f.id = e.formation_id";

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




