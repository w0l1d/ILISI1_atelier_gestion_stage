<?php
function switch_offre(string $status){
    switch ("$status") {
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
            echo 'RÉPONSE EN ATTENTE';
            break;
        case 'WAITING_RESULT':
            echo 'Résultat EN ATTENTE';
           
            break;
        case 'FULL':
            echo 'Plein';
            break;
           
}
}

function switch_candidature(string $status){

    switch ("$status") {
        case 'APPLIED':
            echo 'postuler';
            break;
        case 'NACCEPTED':
            echo 'Pas Retenu ';
            break;
        case 'CANCELED':
            echo 'Annulee';
            break;
        case 'ACCEPTED':
            echo 'Retenu';
            break;
        
            break;
        case 'NAGREED':
            echo 'Pas Accepté';
            break;
        case 'AGREED':
            echo 'Accepté';
            break;
        case 'WAITING':
            echo 'En Attente';
            break;
        default:
       $st= explode("{",$status);
            echo "En Attente{".$st[1];
       
}
}

function switch_stage(string $status){

    switch ("$status") {
        case 'IN_PROGRESS':
            echo 'En cours';
            break;
        case 'FINISHED':
            echo 'Termine ';
            break;
        case 'CANCELED':
            echo 'Annule';
            break;
        case 'DRAFT':
            echo 'planifier';
            break;
        
}
};

function type_stage(string $status){

    switch ("$status") {
        case 'INIT':
            echo 'INITIATION';
            break;
        case 'SUMMER':
            echo 'ÉTÉ';
            break;
        default:
        echo $status;
        
}
};
?>