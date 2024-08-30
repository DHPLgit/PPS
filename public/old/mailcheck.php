<?php

try
{
    $email = new PHPMailer(TRUE);
    $email->isSMTP();
    $email->SMTPDebug = 2;
    $email->SMTPAuth = TRUE;
    $email->SMTPAutoTLS = FALSE;
    $email->SMTPSecure = "tls";
    $email->Host = "smtpout.secureserver.net";
    $email->Port = 80;
    $email->Username = "s1myqgsvv5vn";
    $email->Password = "DP#Ef3yLCTSn";

    $email->setFrom("s1myqgsvv5vn@hctools.in", "Name");
    $email->addAddress("vivekkt.1991@gmil.com", "Name");
    $email->isHTML(TRUE);
    $email->Body = "My HTML Code";
    $email->Subject = "My Subject";
    $email->send();
}
catch (Exception $e)
{
    // $email->ErrorInfo;
}

?>