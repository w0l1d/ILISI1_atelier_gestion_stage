<?php
require_once('../private/shared/auth.functions.php');
$request = explode('?', $_SERVER['REQUEST_URI'])[0];
session_start();

$user = isAuthenticated();

switch ($request) {
    case '/login':
        if ($user != null)

            header('Location: /');
        require __DIR__ . '/../views/login.php';
        die();
    case '/register':
        if ($user != null)
            header('Location: /');
        require_once __DIR__ . '/../views/register.php';
        die();
    case '/entreprises/logo':
        require_once __DIR__ . '/../views/logoManagement.php';
        die();

}


if ($user == null)
    header('Location: /login');


is_authenticated:
switch ($user['type']) {
    case 'etudiant' :
        goto is_student;
    case 'enseignant' :
        goto is_instructor;
    case 'admin' :
        goto is_admin;
}
die();

is_student:
switch ($request) {
    case '':
    case '/dashboard':
    case '/':
      
        require __DIR__ . '/../views/student/index.student.php';
        break;

    case '/test':
        require __DIR__ . '/../views/resp/validate_student.resp.php';
        break;

    default:
        http_response_code(404);
        echo $request."<br>";
        require __DIR__ . '/../views/404.php';
        break;
}

die();

is_instructor:
switch ($request) {
    case '':
    case '/':
    case '/dashboard':
        require __DIR__ . '/../views/resp/dashboard.resp.php';
        break;

    case '/offres' :
        require __DIR__ . '/../views/resp/offre.resp.php';
        break;

    case '/entreprises' :
        require __DIR__ . '/../views/resp/entreprise.resp.php';
        break;

    case '/profile' :
        require __DIR__ . '/../views/resp/profile.resp.php';
        break;

    case '/etudiants' :
        require __DIR__ . '/../views/resp/etudiant.resp.php';
        break;

    case '/test-mail' :
        require __DIR__ . '/../views/testmail.php';
        break;

    default:
        http_response_code(404);
        echo $request."<br>";
        require __DIR__ . '/../views/404.php';
        break;
}
die();
is_admin:
switch ($request) {
    case '':
    case '/dashboard':
    case '/':
        require __DIR__ . '/../views/admin/index.admin.php';
        break;

    default:
        http_response_code(404);
        echo $request."<br>";
        require __DIR__ . '/../views/404.php';
        break;
}


