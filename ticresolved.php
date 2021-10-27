<?php
require 'PHPMailerAutoload.php';
include("config.php");

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$findqry = sprintf("select * from myti.uv_ticket where status_id='4' AND updated_at < DATE_SUB(NOW(),INTERVAL 2 day)");
$userSql = $conn->query($findqry);

while ($data = $userSql->fetch_assoc())
{
  $userid = $data['customer_id'];
  $tic_id = $data['id'];

  $userdata = sprintf("select * from myti.uv_user where id='$userid'");
  $userdetails = $conn->query($userdata);
  $userinfo = $userdetails->fetch_array();

$ticsql = sprintf("UPDATE myti.uv_ticket SET status_id = '5' WHERE status_id = '4' AND id='$tic_id'");
$conn->query($ticsql);


  $useremail = $userinfo['email'];
  $username = $userinfo['first_name'];
  //$useremail = 'avohra@atmc.edu.au';


$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'email-smtp.ap-southeast-2.amazonaws.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'AKIAV44SXO5MILZU3PY4';                 // SMTP username
$mail->Password = 'BBwudcmqlQEa4xMmn/0T069lsL698DLwMUFIJrZ7B9Jh';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('noreply@edfibre.com', 'ATMC Ticket Desk');
$mail->addAddress($useremail, $username);     // Add a recipient

//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
$mail->addBCC('avohra086@gmail.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Ticket Closed';
$mail->Body    = 'Hello <b>'.$username.'</b>,<p>We believe that Request Reference: '.$tic_id.', which you submitted, is now resolved and considered completed.</p></br>
<p>If you believe that this request has not been resolved, please reply back in the same ticket.</p></br>
Kindly rate your Hub 24x7 experience&nbsp; <a href="https://help.edfibre.com/report/rates.php?id='.$tic_id.'" target="_blank"> Click </a></br></br>
<p>Thanks and Regards</p>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

echo $msg = 'Hello <b>'.$username.'</b>,<p>We believe that Request Reference: '.$tic_id.', which you submitted, is now resolved and considered completed.</p></br>
<p>If you believe that this request has not been resolved, please reply back in the same ticket.</p></br>
Kindly rate your Hub 24x7 experience&nbsp; <a href="https://dev-help.edfibre.net/report/rates.php?id='.$tic_id.'">click </a></br></br>
<p>Thanks and Regards, </br> Support Center</p>';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

}
?>



