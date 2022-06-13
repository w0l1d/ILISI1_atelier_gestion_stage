<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendMail(string $mailto, string $body, string $subject) {

    //Load Composer's autoloader
    require __DIR__ . '/../vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        $mail->IsSmtp();
        //Enable verbose debug output
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        // Send email using Outlook SMTP server
        $mail->SMTPDebug = 2;
        $mail->Host       = "smtp.gmail.com";

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->Username = "w0l1d.dev@gmail.com";
        $mail->Password = "yfrdgllnwegajmge";


        $mail->IsHTML(true);
        $mail->setFrom("w0l1d.dev@gmail.com");
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($mailto);

       /* //Attachments
        $mail->addAttachment($attach, $attachName);*/
        $mail->Send();

        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

