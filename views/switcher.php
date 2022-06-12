<?php
function switch_statuts(string $status){
    switch ($status) {
        case 'NEW':
            echo 'Nouveau';
            break;
        case 'CLOSED':
            echo 'Fermee';
            break;
        case 'CANCELED':
            echo 'Annulee';
            break;
        case 'WAITING_RESPONSE':
            echo 'EN ATTENTE DE RÉPONSE';
            break;
        case 'WAITING_RESULT':
            echo 'Résultat EN ATTENTE';
           
            break;
        case 'FULL':
            echo 'Plein';
            break;
           
}
}
?>