<?php
//send_mail.php

if(isset($_POST['send_email']))
{
	require '../vendor/PHPMailerClass/class.phpmailer.php';

	// get the data
	$recipients = $_POST['email_recipients'];
	$sender = $_POST['email_sender'];
	$subject = $_POST['email_subject'];
   $body = $_POST['email_body'];
   $attachment = "
   \r\n\r\n
   <br>A new campaign with the subject <b>{$subject}</b> is about to go out.\r\nPlease Authourize this campaign.
   ";

	// collect all the recipients emails
   $recipientEmails = json_encode($recipients);
   
	$mail = new PHPMailer;
	$mail->CharSet = "UTF-8";
	$mail->Encoding = "base64";
	$mail->IsSMTP();
	$mail->Host = 'mail.miratechnologies.com.ng';
	$mail->Port = 465;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'ssl';
	$mail->Username = 'bulkmailer@miratechnologies.com.ng';
	$mail->Password = '8WcH*IOT62uK';
	$mail->From = 'bulkmailer@miratechnologies.com.ng';
	$mail->FromName = $sender;
	$mail->AddAddress("ebukaodini@gmail.com", "Developer Admin");
	$mail->addReplyTo('bulkmailer@miratechnologies.com.ng', 'Mira Technologies');
	$mail->WordWrap = 50;
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	// clean the body codes
	$body = str_replace("class=\"bulkmailer\"", "", $body);
	$body = str_replace("class=\"_txt\"", "", $body);
	$body = str_replace("class=\"_imglink\"", "", $body);
	$body = str_replace("class=\"_img\"", "", $body);
	$body = str_replace("class=\"default _add_block _row\"", "", $body);
	$body = str_replace("class=\"_txtblock\"", "", $body);
	$body = str_replace("class=\"_content\"", "", $body);
	$body = str_replace("class=\"_button\"", "", $body);
	$body = str_replace("class=\"_divider\"", "", $body);
	$body = str_replace("class=\"_spacer\"", "", $body);
	$body = str_replace("class=\"_table\"", "", $body);
	$body = str_replace("class=\"_add_content\"", "", $body);
	$mail->Body = $body . $attachment;
	$mail->AltBody = "<br>A new campaign with the subject <b>{$subject}</b> is about to go out.\r\nPlease Authourize this campaign.";
	$result = $mail->Send();

	$status = ($result["code"] == '400') ? "ERROR" : "OK" ;
	$mailType = "";

	include 'dbmodel.php';
	$model = new DBModel();
	$add = $model->addNewMailCampaign($subject, $sender, $recipientEmails, $body, "Pending"); # Authorized

	if ($status == "OK" || $add['flag'] == true) {
		exit(json_encode([
			"flag"=>true
		]));
	}
	else {
		exit(json_encode([
			"flag"=>false,
			"data"=>$output
		]));
	}
}

?>