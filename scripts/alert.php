<?php

require_once 'swiftmailer/swift_required.php';
require_once 'database.php';

$ip = $_SERVER['REMOTE_ADDR'];

$result = $db->query(" SELECT * FROM visitors WHERE ip='{$ip}'; ");

// If returning visitor
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    if ($row['mailsent'] < 50) {
        // mailsent++
        $db->query(" UPDATE visitors SET mailsent = (mailsent + 1) WHERE ip='{$ip}'; ");

        $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
            ->setUsername('watchmypcforme@gmail.com')
            ->setPassword('');

        $mailer = Swift_Mailer::newInstance($transport);

        $message = Swift_Message::newInstance('Alert: Check on your computer')
            ->setFrom(array('watchmypcforme@gmail.com' => 'Watch my PC for me'))
            ->setTo($_POST['email'])
            ->setBody('Alarm triggered by ' . $ip);

        if ($mailer->send($message)) echo("OK");
        else echo("ERROR");
    }
} else { // User is new, so save IP
    $db->query(" INSERT INTO visitors (ip, mailsent) VALUES ('{$ip}', 0); ");
}

?>
