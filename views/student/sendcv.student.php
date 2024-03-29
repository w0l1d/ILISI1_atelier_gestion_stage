<?php

function sendcv(int $offre_id){
  require_once(__DIR__ . '/../../private/shared/DBConnection.php');
  $pdo = getDBConnection();

  $curr_user = $_SESSION['user'];

$nom_prenom=$curr_user['fname']." ".$curr_user['lname'];
 


 


    $query = "SELECT   e.email ,o.title,e.short_name,t.cv,o.start_stage ,o.nbr_stagiaire ,
              o.end_stage, o.type_stage FROM offre o, entreprise e,etudiant t, candidature c
              WHERE  o.id =:id_offre AND o.entreprise_id= e.id AND c.offre_id=o.id AND c.etudiant_id=t.id
              AND t.id =:id_student";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_offre', $offre_id);
    $stmt->bindParam(':id_student', $curr_user['id']);
    $stmt->execute();
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($offre)) {
        $error = "offre `$offre_id` n'est pas trouve";
        echo  $error;
        die();
    }
    else {
     
      
        $attach = __DIR__ . '/../../private/uploads/Docs/CVs/'.$offre['cv'];
        $titre=$offre['title'];
        $entreprise=$offre['short_name'];
        $start=$offre['start_stage'];
        $end=$offre['end_stage'];
        $type=$offre['type_stage'];
        $nbr=$offre['nbr_stagiaire'];

        $img_cndidature = __DIR__.'/../../public/assets/img/image-6.png';
        $imgdataCand = base64_encode(file_get_contents($img_cndidature));

        $imgdataCand = 'src="data:'. mime_content_type($img_cndidature) .';base64,'.$imgdataCand.'"';
      

        $img = __DIR__.'/../../public/assets/img/image-5.png';
        $imgdata = base64_encode(file_get_contents($img));

        $imgdata = 'src="data:'. mime_content_type($img) .';base64,'.$imgdata.'"';
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
        
      <img align='center' border='0' $imgdataCand alt='Logo' title='Tick' style='outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 14%;max-width:  81.2px;' width='81.2' class='v-src-width v-src-max-width'/>
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
      <strong>Nouvelle candidature  :</strong>
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
       Une nouvelle candidature li&eacutee &agrave votre offre de stage avec l'&eacuteel&eacuteement ci-dessous :
     $nom_prenom (nom prenom)  &agrave postuler dans votre offre, pour plus d&squoinformations voir le CV (pi&egravece jointe)
      </p>
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
              <th> Nombre de stagiaires demand&eacutees</th>
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
      N&apos;h&eacuteesitez pas &agrave nous contacter!</span></p>
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
      <p style='font-size: 14px; line-height: 140%;'><span style='font-size: 18px; line-height: 25.2px;'><strong></strong></span></p>
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
        $subject="nouvelle candidature : titre :'$titre' "; 
        require_once(__DIR__ . '/../../views/Mailing.php');
        sendMail($mailto,$body,$subject,$attach);
       
      
   
    }
 
    }