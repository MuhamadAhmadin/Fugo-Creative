<?php
include "classes/class.phpmailer.php";
$mail = new PHPMailer; 
$mail->IsSMTP();
$mail->SMTPSecure = 'ssl'; 
$mail->Host = "smtp.gmail.com"; //host masing2 provider email
$mail->SMTPDebug = 2;
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = "ahmadinhanjuan@gmail.com"; //user email
$mail->Password = "gfaugqyqbavtirhe"; //password email 
$mail->SetFrom("ahmadinhanjuan@gmail.com","Ahmadin HJ"); //set email pengirim
$mail->Subject = "Testing"; //subyek email
$mail->AddAddress("ahmadinations@gmail.com","Muhamad Ahmadin HJ");  //tujuan email
// $file_to_attach = $_FILES['file']['tmp_name'];
// $filename=$_FILES['file']['name'];
$file_to_attach = '../assets/sample.pdf';
$filename='sample pdf brp.pdf';
$mail->AddAttachment( $file_to_attach , $filename );
$mail->MsgHTML("
    <h2>Career Submission</h2>
    <div>From: Ahmadin@gmail.com</div><br>
    <div>Subject: This is subject</div><br>
    <div>Message: This is message</div><br>
");
if($mail->Send()) echo "Message has been sent";
else echo "Failed to sending message";
?>