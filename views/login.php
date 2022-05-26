<?php

/// logout user
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) ||
        !empty($_POST['password']) ||
        !empty($_POST['user-type'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $user_type = $_POST['user-type'];

        require_once('../private/shared/DBConnection.php');
        $pdo = getDBConnection();
        $sup_query = ";";
        switch ($user_type) {
            case 1:
                $user_type = "etudiant";
//                $sup_query = " AND e.IsValidated is true;";
                break;
            case 2:
                $user_type = "enseignant";
                $sup_query = " AND p.id IN (SELECT responsable_id FROM formation);";
                break;
            case 3:
                $user_type = "admin";
                break;
        }

        try {
            $query = "SELECT p.*, e.* FROM person p, $user_type e
                WHERE p.email = :email AND p.password like :pwd AND e.id = p.id" . $sup_query;

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pwd', $password);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            if (!empty($row)) {
                $user = $row;
                $user['type'] = $user_type;
                if (($user_type === "etudiant") && !$user['IsValidated'])
                    $error = "Veuillez contacter votre responsable pour valider votre compte";
                else {
                    $_SESSION['user'] = $user;
                    header('Location: /');
                    die();
                }
            } else
                $error = "email ou mot de passe incorrect";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else
        $error = "all fields are required !!";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Authentication</title>
</head>
<body>
<div>

    <!-- Pills content -->
    <div>
        <form method="post" action="/login">
            <?php if (!empty($error)) { ?>
                <div>
                    <h4> <?php echo "error :: $error"; ?></h4>
                </div>
            <?php } ?>
            <div>
                <select name="user-type">
                    <option value="1">Student</option>
                    <option value="2">Responsable</option>
                    <option value="3">Admin</option>
                </select>
            </div>

            <!-- Email input -->
            <div>
                <input type="email" id="loginName" name="email"/>
                <label for="loginName">Email or username</label>
            </div>
            <!-- Password input -->
            <div>
                <input type="password" id="loginPassword" name="password"/>
                <label for="loginPassword">Password</label>
            </div>

            <!-- Submit button -->
            <button type="submit">Sign in</button>

        </form>
    </div>
</body>
</html>





