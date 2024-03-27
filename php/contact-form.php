<?php

namespace ContactForm;

session_cache_limiter('nocache');
header('Expires: ' . gmdate('r', 0));

header('Content-type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'php-mailer/src/PHPMailer.php';
require 'php-mailer/src/SMTP.php';
require 'php-mailer/src/Exception.php';

// Send From
$email = 'marcnashuk@gmail.com';

// $debug = 2; for debug mode
$debug = 0;

// If contact form don't has the subject input change the value of subject here
$subject = ( isset($_POST['subject']) ) ? $_POST['subject'] : 'Define subject in php/contact-form.php line 29';

$message = '';

foreach($_POST as $label => $value) {
	$label = ucwords($label);

	// Use the commented code below to change label texts. On this example will change "Email" to "Email Address"

	// if( $label == 'Email' ) {               
	// 	$label = 'Email Address';              
	// }

	// Checkboxes
	if( is_array($value) ) {
		// Store new value
		$value = implode(', ', $value);
	}

	$message .= $label.": " . htmlspecialchars($value, ENT_QUOTES) . "<br>\n";
}

$mail = new PHPMailer(true);
try {

	$mail->SMTPDebug = $debug;

	$mail->IsSMTP();                                        
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'marcnashuk@gmail.com';
	$mail->Password = 'qvrp kmui mhro qezq';
	$mail->SMTPSecure = $mail::ENCRYPTION_SMTPS;
	$mail->Port = 465;

	$mail->AddAddress('marcnashuk@gmail.com');

	$mail->AddBCC('MarcNashUK@gmail.com');

	$fromName = ( isset($_POST['name']) ) ? $_POST['name'] : 'Website User';
	$mail->SetFrom($email, $fromName);

	if( isset($_POST['email']) ) {
		$mail->AddReplyTo($_POST['email'], $fromName);
	}

	$mail->IsHTML(true);

	$mail->CharSet = 'UTF-8';

	$mail->Subject = $subject;
	$mail->Body    = $message;

	$mail->Send();
	$arrResult = array ('response'=>'success');

} catch (Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->errorMessage());
} catch (\Exception $e) {
	$arrResult = array ('response'=>'error','errorMessage'=>$e->getMessage());
}

if ($debug == 0) {
	echo json_encode($arrResult);
}