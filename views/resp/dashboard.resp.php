<?php
$curr_user = $_SESSION['user'];

require_once(__DIR__ . '/../../private/shared/DBConnection.php');
$pdo = getDBConnection();

try {
    $query = "SELECT p.lname, p.fname, e.promotion, e.cne, e.IsValidated FROM etudiant e, person p 
                                     WHERE e.id = p.id AND e.formation_id = (SELECT id from formation WHERE responsable_id = :resp_id)
                                     ORDER BY e.id DESC limit 3";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':resp_id', $curr_user['id']);
    $stmt->execute();
    $recent_students = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $error = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <title>intership </title>
</head>
<body>
<div class="container">

    <!-- start of vmenu-->
    <?php require_once __DIR__ . '/parts/sidebar.html'?>
    <!-- end of vmenu-->

    <main>
        <h1>Dashboard</h1>
        <div class="date">
            <input type="date">
        </div>
        <div class="insights">
            <!--internship-->
            <div class="internship">
                <span class="material-icons-sharp">lightbulb_circle</span>
                <div class="middle">
                    <div class="left">
                        <h3> internship</h3>
                        <h1>45</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="number">
                            <p>81%</p>
                        </div>
                        <small class="text-muted">Ended</small>
                    </div>
                </div>

            </div>
            <!--End of internship-->

            <!--validation-->
            <div class="validation">
                <span class="material-icons-sharp">done</span>
                <div class="middle">
                    <div class="left">
                        <h3> validation tasks</h3>
                        <h1>5</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="number">
                            <p>5</p>

                        </div>
                        <small class="text-muted">last 24 Hours</small>
                    </div>
                </div>

            </div>
            <!--End of validation-->
            <!--offer-->
            <div class="offer">
                <span class="material-icons-sharp">loyalty</span>
                <div class="middle">
                    <div class="left">
                        <h3>internship offer </h3>
                        <h1>5</h1>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="number">
                            <p>81%</p>
                        </div>
                        <small class="text-muted">deadline</small>

                    </div>
                </div>

            </div>
            <!--End of offer-->
        </div>
        <div class="recent-event">
            <h2> offres de stage recentes</h2>
            <table>
                <thead>
                <tr>
                    <th>Num</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>event</td>
                    <td class="warning">status</td>
                    <td class="primary"> details</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>event</td>
                    <td class="warning">status</td>
                    <td class="primary"> details</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>event</td>
                    <td class="warning">status</td>
                    <td class="primary"> details</td>
                </tr>
                </tbody>
            </table>
            <a href="#"> Show All</a>
        </div>
    </main>
    <!--------------------End of main------------->
    <div class="right">
        <div class="top">
            <button id="menu-btn">
                <span class="material-icons-sharp">menu</span>
            </button>
            <div class="theme-toggler">
                <span class="material-icons-sharp active">light_mode</span>
                <span class="material-icons-sharp ">dark_mode</span>
            </div>
            <div class="profile">
                <div class="info">
                    <p> Hey, <?php echo "{$curr_user['lname']} {$curr_user['fname']}" ?></p>
                    <small class="text-muted"><?php echo $curr_user['type']; ?></small>
                </div>
                <div class="profile-photo">
                    <img src="/assets/img/profile.jpg">
                </div>
            </div>
        </div>
        <!--------------------End of TOP------------->
        <div class="recent-updates">
            <h2> recent Companies</h2>
            <div class="updates">
                <div class="update">
                    <div class="profile-photo">
                        <img src="/assets/img/comp1.jpg">
                    </div>
                    <div class="message">
                        <p> company1 </p>
                        <small class="text-muted">added 21-05-2022 </small>
                    </div>
                </div>
                <!-- End comp1-->
                <div class="update">
                    <div class="profile-photo">

                        <img src="/assets/img/comp2.jpg">
                    </div>
                    <div class="message">
                        <p> company2</p>
                        <small class="text-muted">added 21-05-2022 </small>
                    </div>
                </div>
                <!-- End comp2-->
                <div class="update">
                    <div class="profile-photo">

                        <img src="/assets/img/comp1.jpg">
                    </div>
                    <div class="message">
                        <p> company3</p>
                        <small class="text-muted">added 21-05-2022 </small>
                    </div>
                </div>
                <!-- End comp3-->
            </div>
        </div>
        <!-- End of recent companies-->
        <div class="recent-add">
            <h2>Recent added Students</h2>

            <?php

            if (empty($recent_students)) {
                echo "aucun etudiant";
            } else
                foreach ($recent_students as $rStud) {
                    ?>
                    <!--Element-->
                    <div class="element">
                        <div class="icon">
                            <span class="material-icons-sharp">settings</span>
                        </div>
                        <div class="right">
                            <div class="info">
                                <h3> <?php echo "{$rStud['lname']} {$rStud['fname']}" ?></h3>
                                <small class="text-muted"><?php echo "{$rStud['cne']} --- {$rStud['promotion']} " ?></small>
                            </div>

                        </div>
                    </div>
                    <!-- End of element-->
                    <?php
                }

            ?>


        </div>
    </div>

</div>
<script src="/assets/js/code.js"></script>
</body>
</html>