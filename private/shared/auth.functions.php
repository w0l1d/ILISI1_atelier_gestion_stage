<?php


function isAuthenticated () {
//    if(time()-$_SESSION["login_time_stamp"] >600)
//    {
//        session_unset();
//        session_destroy();
//        header("Location:login.php");
//    }

    return empty($_SESSION['user'])? null: $_SESSION['user'];
}

function isStudent() {
    $user = isAuthenticated();
    if ($user != null && $user['type'] == 'etudiant') {
        return $user;
    }
    return null;
}
