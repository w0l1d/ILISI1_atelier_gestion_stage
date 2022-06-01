<?php
if (isset($_GET['id'])) {
    require_once(__DIR__ . '/../private/shared/DBConnection.php');
    $pdo = getDBConnection();
    $id = $_GET['id'];

    $query = "SELECT logo FROM entreprise where id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);


    if ($stmt->execute()) {

        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        $filepath = __DIR__ . '/../private/uploads/images/logo/' . $file['logo'];

        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($filepath));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);

        }

        exit;
    }

}