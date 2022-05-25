<?php
check_user_connected:
if (($user = isAuthenticated()) != null) {
    header('Location: /');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ///TODO :: validate fields
    if (!empty($_POST['cin']) ||
        !empty($_POST['lname']) ||
        !empty($_POST['fname']) ||
        !empty($_POST['date-naiss']) ||
        !empty($_POST['email']) ||
        !empty($_POST['password']) ||
        !empty($_POST['phone']) ||
        !empty($_POST['formation']) ||
        !empty($_POST['cne']) ||
        !empty($_POST['promotion'])) {

        $cin        =   $_POST['cin'];
        $cne        =   $_POST['cne'];
        $lname      =   $_POST['lname'];
        $fname      =   $_POST['fname'];
        $phone      =   $_POST['phone'];
        $email      =   $_POST['email'];
        $password   =   $_POST['password'];
        $formation  =   strtoupper($_POST['formation']);
        $promotion  =   $_POST['promotion'];
        $date_naiss =   $_POST['date-naiss'];


        require_once('../private/shared/DBConnection.php');
        $pdo = getDBConnection();

        try {

            $pdo->beginTransaction();

            $query = "SELECT id FROM formation WHERE short_title = :short_title";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':short_title', $formation);
            $stmt->execute();
            $formation_id = $stmt->fetch(PDO::FETCH_ASSOC);
            if (empty($formation_id)) {
                $error = "formation n'existe pas";
                $pdo->rollBack();
                goto skip_process;
            }

            $query = "INSERT INTO person 
                    (id, fname,lname,cin,date_naiss,email,phone,password)
                    VALUES (null,:fn,:ln,:cin,:dt_ns,:email,:phone,:pwd)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':fn', $fname);
            $stmt->bindParam(':ln', $lname);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':dt_ns', $date_naiss);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':pwd', $password);
            $stmt->execute();

            $query = "INSERT INTO etudiant (id, formation_id, cne, promotion)
                    VALUES ((select id from person where cin = :cin),:formation,:cne,:promo)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':cin', $cin);
            $stmt->bindParam(':cne', $cne);
            $stmt->bindParam(':promo', $promotion);
                $stmt->bindParam(':formation', $formation_id['id']);
            $stmt->execute();

            $pdo->commit();
            $msg = 'compte est bien cree';
        } catch (Exception $e) {
            $pdo->rollback();
            $error = $e->getMessage();
        }
    } else
        $error = "all fields are required !!";
}
skip_process:
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
<div >

    <!-- Pills content -->
    <div>
        <form method="post">
            <?php if (!empty($error)) {?>
                <div>
                    <h4> <?php echo "error :: $error";?></h4>
                </div>
            <?php } elseif (!empty($msg)) {?>
                <div>
                    <h4> <?php echo "message :: $msg";?></h4>
                </div>
            <?php }?>

            <div>
                <input type="text" id="cin" name="cin" required/>
                <label  for="cin">cin</label>
            </div>
            <div>
                <input type="text" id="cne" name="cne" required/>
                <label  for="cne">cne</label>
            </div>
            <div>
                <input type="text" id="lname" name="lname" required/>
                <label  for="lname">lname</label>
            </div>
            <div>
                <input type="text" id="fname" name="fname" required/>
                <label  for="fname">fname</label>
            </div>
            <div>
                <input type="text" id="phone" name="phone" required/>
                <label  for="phone">phone</label>
            </div>
            <div>
                <input type="email" id="email" name="email" required/>
                <label  for="email">email</label>
            </div>
            <div>
                <input type="password" id="password" name="password" required/>
                <label  for="password">password</label>
            </div>
            <div>
                <input type="number" id="promotion" name="promotion" required/>
                <label  for="promotion">promotion</label>
            </div>
            <div>
                <input type="date" id="date-naiss" name="date-naiss" required/>
                <label  for="date-naiss">date-naiss</label>
            </div>
            <div>
                <input type="text" minlength="2" maxlength="15" id="formation" required name="formation"/>
                <label  for="formation">formation</label>
            </div>

            <!-- Submit button -->
            <button type="submit" >Sign in</button>

        </form>
    </div>
</body>
</html>












