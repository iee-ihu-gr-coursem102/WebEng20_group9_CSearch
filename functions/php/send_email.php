<?php

/**
 * Function to send email
 * @param type $mail_address:   recipient email address
 * @param type $message         the message to send
 * @param type $mail            mail object
 * 
 */
function send_email($mail_address, $message, $subject, $mail) {
    try {
        $mail->IsSMTP();
        $mail->CharSet = "UTF-8";


        //Set the hostname of the mail server  
        $mail->Host = $_SESSION['smtp'];
        $mail->Port = $_SESSION['port'];

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;


        /* Αποστολέας */
//    $sender = $_SESSION['sender'];
        $mail->setFrom($_SESSION['sender'], 'CSearch - All about music events');


        /* Παραλήπτης */
        $mail->addAddress($mail_address, 'Dear Customer');
        /* Θέμα μηνύματος */
        $mail->Subject = $subject;


        $mail->IsHTML(true);


        $mail->Body = $message;

        if ($mail->Send()) {
            echo "Please check your email";
        } else {
//        echo "Mail Error - >" . $mail->ErrorInfo;
            echo "Please try again";
        }
    } catch (Exception $exc) {

        echo $exc->getTraceAsString();
        echo "Please try once more";
    }
}
