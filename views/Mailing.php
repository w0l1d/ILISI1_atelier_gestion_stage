<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail(string $mailto, string $body, string $subject, string $attach, string $attachName) {

    //Load Composer's autoloader
    require __DIR__ . '/../vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        $mail->IsSmtp();
        //Enable verbose debug output
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // Send email using Outlook SMTP server
        $mail->Host = 'smtp-mail.outlook.com';
        $mail->SMTPDebug = 2;

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->Username = "ilisi1_atelier@hotmail.com";
        $mail->Password = "Cz8JEvjwT427kGm";


        $mail->IsHTML(true);
        $mail->setFrom("ilisi1_atelier@hotmail.com");
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($mailto);

        //Attachments
        $mail->addAttachment($attach, $attachName);
        $mail->Send();

        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

