<?php


function isAuthenticated () {
    return empty($_SESSION['user'])? null: $_SESSION['user'];
}

function isStudent() {
    $user = isAuthenticated();
    if ($user != null && $user['type'] == 'etudiant') {
        return $user;
    }
    return null;
}
