


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Resultat de l'Offre</title>
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col" style="margin-bottom: 1.5rem;background: var(--bs-gray-700);color: var(--bs-white);">
                <h1 class="display-4">Resultat de l'Offre `2` :</h1>
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
                        <ul class="list-unstyled" id="list-non-retenue">
                            <li style="margin-bottom: 1rem;">
                                <div class="card d-flex flex-row">
                                    <div class="card-header"><img class="img-thumbnail img-fluid" src="assets/img/profile.jpg" style="max-width: 100px;max-height: 100px;min-width: auto;min-height: auto;"></div>
                                    <div class="card-body">
                                        <h4 class="card-title">Title</h4>
                                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                        <p>les informations</p>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="col" style="margin-bottom: 1rem;">
                <div class="card" style="height: 100%;">
                    <div class="card-header">
                        <h4>Etudiant Retenue</h4>
                        <h6 class="text-muted mb-2">MAX = 3</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled" id="list-retenue">
                            <li style="margin-bottom: 1rem;">
                                <div class="card d-flex flex-row">
                                    <div class="card-header"><img class="img-thumbnail img-fluid" src="assets/img/profile.jpg" style="max-width: 100px;max-height: 100px;min-width: auto;min-height: auto;"></div>
                                    <div class="card-body">
                                        <h4 class="card-title">Title</h4>
                                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                        <p>les informations</p>
                                    </div>
                                </div>
                            </li>
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
                        <ol id="list-attente">
                            <li style="margin-bottom: 1rem;">
                                <div class="card d-flex flex-row">
                                    <div class="card-header"><img class="img-thumbnail img-fluid" src="assets/img/profile.jpg" style="max-width: 100px;max-height: 100px;min-width: auto;min-height: auto;"></div>
                                    <div class="card-body">
                                        <h4 class="card-title">Title</h4>
                                        <h6 class="text-muted card-subtitle mb-2">Subtitle</h6>
                                        <p>les informations</p>
                                    </div>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-12 text-end flex-row-reverse flex-grow-0 flex-fill align-items-stretch align-content-stretch"><button class="btn btn-primary" type="button" data-bs-target="#modal-1" data-bs-toggle="modal">Enregistrer</button></div>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Voulez-vous vraiment enregistrer ?
                        <br>vous pouvez pas modifier apres entregistrer.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-primary" type="button">Sauvegarder</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>