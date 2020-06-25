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
		public $status = array('codigo_status' => null, 'descricao_status' => '');
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
		header('Location: index.php');
	}

	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer;

		//Server settings
		// $mail->SMTPDebug = 4;                      				// Enable verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
		$mail->Host = gethostbyname('smtp.gruporodrix.net');       	// Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = 'lucas.rodrigues=gruporodrix.net';      // SMTP username
		$mail->Password   = 'Renata.1983';                          // SMTP password
		$mail->SMTPSecure = 'tls';         							// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		//Recipients
		$mail->setFrom('lucas.rodrigues@gruporodrix.net', 'Rodrix Mailer');
		$mail->addAddress($mensagem->__get('para'));     // Add a recipient
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
		$mail->Body    =  $mensagem->__get('mensagem');
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			$mensagem->status['codigo_status'] = 2;
			$mensagem->status['descricao_status'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
		} else {
			$mensagem->status['codigo_status'] = 1;
			$mensagem->status['descricao_status'] = 'Message has been sent';
		}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>App Mail Send</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>
	<div class="container">
		<div class="py-3 text-center">
			<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
			<h2>Send Mail</h2>
			<p class="lead">Seu app de envio de e-mails particular!</p>
		</div>
		<div class="row">
			<div class="col-md-12">
			<? if($mensagem->status['codigo_status'] == 1) { ?>
				<div class="container">
					<h1 class="display-4 text-success">Sucesso</h1>
					<p><?= $mensagem->status['descricao_status']?></p>
					<a href="index.php" class="btn btn-success btn-lg mb-5 text-white">Voltar</a>
				</div>
			<? } ?>
			<? if($mensagem->status['codigo_status'] == 2) { ?>
				<div class="container">
					<h1 class="display-4 text-danger">Erro!</h1>
					<p><?= $mensagem->status['descricao_status']?></p>
					<a href="index.php" class="btn btn-danger btn-lg mb-5 text-white">Voltar</a>
				</div>
			<? } ?>
			</div>
		</div>
	</div>
</body>
</html>