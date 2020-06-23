<?php

	require "./lib/PHPMailer/Exception.php";
	require "./lib/PHPMailer/OAuth.php";
	require "./lib/PHPMailer/PHPMailer.php";
	require "./lib/PHPMailer/POP3.php";
	require "./lib/PHPMailer/SMTP.php";

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	// print_r($_POST);

	class Mensagem{
		private $para = null;
		private $assunto = null;
		private $mensagem = null;

		public function __get($attr){
			return $this->$attr;
		}

		public function __set($attr, $value){
			$this->$attr = $value;
		}

		public function mensagemValida(){
			if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)){
				return false;
			}
			return true;
		}
	}

	$mensagem = new Mensagem();
	$mensagem->__set('para', $_POST['para']);
	$mensagem->__set('assunto', $_POST['assunto']);
	$mensagem->__set('mensagem', $_POST['mensagem']);

	// print_r($mensagem);

	if(!$mensagem->mensagemValida()){
		echo 'Mensagem não é Válida';
		die();
		// header('Location:index.php');
	}

	$mail = new PHPMailer(true);

	try {
	    //Server settings
	    $mail->SMTPDebug = 2;										// Enable verbose debug output
	    $mail->isSMTP();                                            // Send using SMTP
	    $mail->Host       = 'smtp.gmail.com';                    	// Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	    $mail->Username   = 'lucasrodrigues1985.lfr@gmail.com';     // SMTP username
	    $mail->Password   = 'lucas@1115';                           // SMTP password
	    $mail->SMTPSecure = 'tls';         							// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	    //Recipients
	    $mail->setFrom('lucasrodrigues1985.lfr@gmail.com', 'Web Completo Remetente');
	    $mail->addAddress('lucasrodrigues1985.lfr@gmail.com', 'Web Completo Destinatário');     // Add a recipient
	    // $mail->addAddress('ellen@example.com');               // Name is optional
	    // $mail->addReplyTo('info@example.com', 'Information');
	    // $mail->addCC('cc@example.com');
	    // $mail->addBCC('bcc@example.com');

	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $mensagem->__get('assunto');
		$mail->Body    = $mensagem->__get('mensagem');
	    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	    $mail->send();
	    echo 'Message has been sent';
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}