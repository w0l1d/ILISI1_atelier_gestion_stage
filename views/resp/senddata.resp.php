<?php

require_once(__DIR__ . '/../../private/shared/DBConnection.php');

$pdo = getDBConnection();

$curr_user = $_SESSION['user'];
if (empty($_GET['id'])) {
    
    header('Location: /dashboard');
}
$offre_id = $_GET['id'];
$nom_prenom=$curr_user['fname']." ".$curr_user['lname'];

 


    $query = "SELECT  r.key , e.email ,o.title,e.short_name ,o.start_stage ,o.nbr_stagiaire ,o.end_stage, o.type_stage ,f.short_title FROM  offreresults r ,offre o, entreprise e,formation f
                WHERE  r.offre_id=o.id AND o.id =:id_offre AND o.entreprise_id= e.id AND f.id=o.formation_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_offre', $offre_id);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($offre)) {
        $error = "offre `$offre_id` n'est pas trouve";
        echo  $error;
        die();
    }
    else {

        $key='84d0670b-e911-11ec-9d50-9822ef';//['key'];
        $titre=$offre['title'];
        $entreprise=$offre['short_name'];
        $start=$offre['start_stage'];
        $end=$offre['end_stage'];
        $type=$offre['type_stage'];
        $nbr=$offre['nbr_stagiaire'];
        $formation=$offre['short_title'];
        $adresse="http://localhost:8580/offres/resultat?key=".$key;
        $img = file_get_contents(__DIR__.'/../../public/assets/img/image-5.png');
        $imgdata = base64_encode($img);
        $imgdata = "src='data:image/*;base64,$imgdata'";
        $body="<html >
        <head>
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
      
        <style type='text/css'>
        @media only screen and (min-width: 620px) {
    .u-row {
      width: 600px !important;
    }
    .u-row .u-col {
      vertical-align: top;
    }
  
    .u-row .u-col-100 {
      width: 600px !important;
    }
  
  }
  
  @media (max-width: 620px) {
    .u-row-container {
      max-width: 100% !important;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
    .u-row .u-col {
      min-width: 320px !important;
      max-width: 100% !important;
      display: block !important;
    }
    .u-row {
      width: calc(100% - 40px) !important;
    }
    .u-col {
      width: 100% !important;
    }
    .u-col > div {
      margin: 0 auto;
    }
    .no-stack .u-col {
      min-width: 0 !important;
      display: table-cell !important;
    }
  
    .no-stack .u-col-100 {
      width: 100% !important;
    }
  
  }
  body {
    margin: 0;
    padding: 0;
  }
  
  table,
  tr,
  td {
    vertical-align: top;
    border-collapse: collapse;
  }
  
  p {
    margin: 0;
  }
  
  .ie-container table,
  .mso-container table {
    table-layout: fixed;
  }
  
  * {
    line-height: inherit;
  }
  
  a[x-apple-data-detectors='true'] {
    color: inherit !important;
    text-decoration: none !important;
  }
  
  table, td { color: #000000; } a { color: #cca250; text-decoration: none; } @media (max-width: 480px) { #u_content_image_4 .v-container-padding-padding { padding: 10px !important; } #u_content_image_4 .v-src-width { width: auto !important; } #u_content_image_4 .v-src-max-width { max-width: 45% !important; } #u_content_image_3 .v-container-padding-padding { padding: 46px 10px 10px !important; } #u_content_image_3 .v-src-width { width: auto !important; } #u_content_image_3 .v-src-max-width { max-width: 29% !important; } #u_content_heading_3 .v-container-padding-padding { padding: 10px 20px !important; } #u_content_heading_3 .v-font-size { font-size: 28px !important; } #u_content_text_3 .v-container-padding-padding { padding: 10px 22px 26px !important; } #u_content_heading_2 .v-container-padding-padding { padding: 22px 22px 10px !important; } #u_content_heading_2 .v-font-size { font-size: 24px !important; } }
      </style>
    
    
  
   <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap'rel='stylesheet' type='text/css'> 
  
  </head>
  
  <body class='clean-body u_body' style='margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #f9f9f9;color: #000000'>
   
    <table style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #f9f9f9;width:100%' cellpadding='0' cellspacing='0'>
    <tbody>
    <tr style='vertical-align: top'>
      <td style='word-break: break-word;border-collapse: collapse !important;vertical-align: top'>
      
      
  
  <div class='u-row-container' style='padding: 0px;background-color: transparent'>
    <div class='u-row no-stack' style='Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;'>
      <div style='border-collapse: collapse;display: table;width: 100%;background-color: transparent;'>
       
  <div class='u-col u-col-100' style='max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;'>
    <div style='width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'>
   <div style='padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'> 
    
  <table id='u_content_image_4' style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:sans-serif;' align='left'>
          
  <table width='100%'' cellpadding='0' cellspacing='0' border='0'>
    <tr>
      <td style='padding-right: 0px;padding-left: 0px;' align='center'>
        
        <img align='center' border='0' $imgdata alt='Logo' title='Logo' style='outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 33%;max-width: 191.4px;' width='191.4' class='v-src-width v-src-max-width'/>
        
      </td>
    </tr>
  </table>
  
        </td>
      </tr>
    </tbody>
  </table>
  
   </div> 
    </div>
  </div>
   
      </div>
    </div>
  </div>
  
  
  
  <div class='u-row-container' style='padding: 0px;background-color: transparent'>
    <div class='u-row' style='Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: transparent;'>
      <div style='border-collapse: collapse;display: table;width: 100%;background-color: transparent;'>
        
  <div class='u-col u-col-100' style='max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;'>
    <div style='background-color: #fffefe;width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'>
   <div style='padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'> 
    
  <table id='u_content_image_3' style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:40px 10px 10px;font-family:sans-serif;' align='left'>
          
  <table width='100%'' cellpadding='0' cellspacing='0' border='0'>
    <tr>
      <td style='padding-right: 0px;padding-left: 0px;' align='center'>
        
      <i class='fas fa-check fa-4x ' style='font-size:60px;color:#cca250;'></i>
      </td>
    </tr>
  </table>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table id='u_content_heading_3' style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:10px 55px;font-family:sans-serif;' align='left'>
          
    <h1 class='v-font-size' style='margin: 0px; line-height: 160%; text-align: center; word-wrap: break-word; font-weight: normal; font-family:sans-serif; font-size: 33px;'>
      <strong>Liste des Condidatures :<br />'$entreprise'<br /></strong>
    </h1>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table id='u_content_text_3' style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:10px 60px 50px;font-family:sans-serif;' align='left'>
          
    <div style='color: #444444; line-height: 170%; text-align: center; word-wrap: break-word;'>
      <p style='font-size: 14px; line-height: 170%;'><span style='font-size: 16px; line-height: 27.2px;'>
      le délai pour l'offre que vous avez faite a expiré
      merci de préciser la liste des étudiants admis, en attente et refusés sur la page suivante :
      <a href='$adresse'> resultats </a> <br>
      <h2>note:</h2>   <h4 style='color:#cca250';>Après sauvegarde,aucune modification ne sera prise en compte <h4></span></p>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width=100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:sans-serif;' align='left'>
          
    <div>
      
  <style>
  
      .table-infos {
  
          border-spacing: 3rem 1rem;
          font-size: calc(0.4em + 1vmin);
      }
  
      .table-infos th {
          opacity: 0.3;
          font-size: calc(0.25em + 1vmin);
          text-align: start;
      }
  
  </style>
  
  <div style='display: flex; justify-content: center;'>
      <table class='table-infos'>
          <tr>
              <th>Titre</th>
              <td>'$titre'</td>
          </tr>
          <tr>
              <th>type de stage</th>
              <td>'$type'</td>
          </tr>
          <tr>
              <th>Date de debut</th>
              <td>'$start'</td>
          </tr>
          <tr>
              <th>Date de Fin</th>
              <td>'$end'</td>
          </tr>
          <tr>
              <th> Nombre de stagiaires demandés</th>
              <td>'$nbr'</td>
          </tr>
  
      </table>
  
  </div>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
   </div> 
    </div>
  </div>
   
      </div>
    </div>
  </div>
  
  
  
  <div class='u-row-container' style='padding: 0px;background-color: transparent'>
    <div class='u-row' style='Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;'>
      <div style='border-collapse: collapse;display: table;width: 100%;background-color: transparent;''>
       
  <div class='u-col u-col-100' style='max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;''>
    <div style='width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;''>
    <div style='padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;''> 
    
  <table id='u_content_heading_2' style='font-family:sans-serif;'' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:40px 55px 10px;font-family:sans-serif;'' align='left'>
          
    <h1 class='v-font-size' style='margin: 0px; line-height: 160%; text-align: center; word-wrap: break-word; font-weight: normal; font-family: sans-serif; font-size: 26px;'>
      <strong>Besoin d'autre information ?</strong>
    </h1>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:0px 60px 20px;font-family:sans-serif;' align='left'>
          
    <div style='color: #444444; line-height: 170%; text-align: center; word-wrap: break-word;'>
      <p style='font-size: 14px; line-height: 170%;'><span style='font-size: 16px; line-height: 27.2px;'>
      N'hésitez pas à nous contacter!</span></p>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
    </div> 
    </div>
  </div>
   
      </div>
    </div>
  </div>
  
  
  
  <div class='u-row-container' style='padding: 0px;background-color: transparent'>
    <div class='u-row' style='Margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #111114;'>
      <div style='border-collapse: collapse;display: table;width: 100%;background-color: transparent;''>
        
  <div class='u-col u-col-100' style='max-width: 320px;min-width: 600px;display: table-cell;vertical-align: top;'>
    <div style='width: 100% !important;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'>
    <div style='padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;border-radius: 0px;-webkit-border-radius: 0px; -moz-border-radius: 0px;'> 
    
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:32px 10px 0px;font-family:'Montserrat',sans-serif;' align='left'>
          
    <div style='color: #ffffff; line-height: 140%; text-align: center; word-wrap: break-word;'>
      <p style='font-size: 14px; line-height: 140%;'><span style='font-size: 18px; line-height: 25.2px;'><strong>$formation</strong></span></p>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding'style='overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:'sans-serif;' align='left'>
          
    <div style='color: #b0b1b4; line-height: 180%; text-align: center; word-wrap: break-word;'>
      <p style='font-size: 14px; line-height: 180%;'>$nom_prenom</p>
  <p style='font-size: 14px; line-height: 180%;'>contactez le Responsable</p>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:sans-serif;' align='left'>
          
  <div align='center'>
    <div style='display: table; max-width:211px;'>
     
      <table align='left' border='0' cellspacing='0' cellpadding='0' width='32' height='32' style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 21px'>
        <tbody><tr style='vertical-align: top'><td align='left' valign='middle' style='word-break: break-word;border-collapse: collapse !important;vertical-align: top'>
          <a href=''>
          <i class='fab fa-facebook fa-2x text-gray-300'></i>
          </a>
        </td></tr>
      </tbody></table>
      
      <table align='left' border='0' cellspacing='0' cellpadding='0' width='32' height='32'style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 21px'>
        <tbody><tr style='vertical-align: top'><td align='left' valign='middle' style='word-break: break-word;border-collapse: collapse !important;vertical-align: top'>
          <a href=''>
          <i class='fab fa-instagram fa-2x text-gray-300'></i>
          </a>
        </td></tr>
      </tbody></table>
      
      <table align='left' border='0' cellspacing='0' cellpadding='0' width='32' height='32' style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 21px'>
        <tbody><tr style='vertical-align: top'><td align='left' valign='middle' style='word-break: break-word;border-collapse: collapse !important;vertical-align: top'>
          <a href=''>
          <i class='fas fa-envelope fa-2x text-gray-300'></i>
          </a>
        </td></tr>
      </tbody></table>
      
      <table align'left' border='0' cellspacing='0' cellpadding='0' width='32' height='32' style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;margin-right: 0px'>
        <tbody><tr style='vertical-align: top'><td align='left' valign='middle' style='word-break: break-word;border-collapse: collapse !important;vertical-align: top'>
          <a href=''>
          <i class='fab fa-linkedin fa-2x text-gray-300'></i>
          </a>
        </td></tr>
      </tbody></table>
       
    </div>
  </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:sans-serif;' align='left'>
          
    <table height='0px' align='center' border='0' cellpadding='0' cellspacing='0' width='82%' style='border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 1px solid #9495a7;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%'>
      <tbody>
        <tr style='vertical-align: top'>
          <td style='word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%'>
            <span>&#160;</span>
          </td>
        </tr>
      </tbody>
    </table>
  
        </td>
      </tr>
    </tbody>
  </table>
  
  <table style='font-family:sans-serif;' role='presentation' cellpadding='0' cellspacing='0' width='100%' border='0'>
    <tbody>
      <tr>
        <td class='v-container-padding-padding' style='overflow-wrap:break-word;word-break:break-word;padding:0px 10px 13px;font-family:sans-serif;' align='left'>
          
    <div style='color: #b0b1b4; line-height: 180%; text-align: center; word-wrap: break-word;'>
      <p style='font-size: 14px; line-height: 180%;'>&copy; 2022 FSTM-STAGE</p>
    </div>
  
        </td>
      </tr>
    </tbody>
  </table>
  
    </div> 
    </div>
  </div>
   
      </div>
    </div>
  </div>
  
  
      
      </td>
    </tr>
    </tbody>
    </table>";

        $mailto=$offre['email'];
        $subject="liste des candidature pour votre offre : titre :'$titre' "; 
        require_once(__DIR__ . '/../../views/Mailing.php');
        sendMail($mailto,$body,$subject);
       
      
    $req = "UPDATE offre SET statue = 'WAITING_RESPONSE' where id = :offre_id";
    $stmt1 = $pdo->prepare($req);
    $stmt1->bindParam(':offre_id', $offre_id);
    if (!$stmt1->execute()) {
        $error = 'Impossible de changer le statue ';
        header('Location: /dashboard');
    }

    $msg = "statue de l'offre est modifier ";
    header('Location: /dashboard');
}
 

  
 
