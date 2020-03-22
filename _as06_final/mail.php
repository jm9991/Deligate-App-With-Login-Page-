<?php
exit(); // do nothing
$to      = 'jmusarra@svsu.edu';
$subject = 'svsu/t registration';
$message = 'click this link to confirm';
$headers = 'From: gpcorser@svsu.edu' . "\r\n" .
    'Reply-To: gpcorser@svsu.edu' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>