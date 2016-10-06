<?php

namespace boFramework\io;

use Exception;
use PHPMailer;

/**
 *
 *
 */
class boEmailer{

	public $mailer;
	public $isSMTP = false;
	public $SMTPHost = "smtp.lixium.fr";

	/**
	 * Constructor
	 */
	function __construct($file, $data = array()){

		if(!class_exists('PHPMailer')){
			throw new Exception('La classe PHPMailer n\'est pas incluse.');
			//return false;
		}
		$this->mailer = new PHPMailer();

		$content = file_get_contents($file);
		foreach($data as $key => $value){
			$content = str_replace("{{{$key}}}", $value, $content);
		}

		$contenu = array();
		preg_match('/<title>(?<titre>.*)<\/title>(.*)<body.*>(?<corps>.*)<\/body>/si',$content,$contenu);

		$this->mailer->Subject = $contenu['titre'];
		$this->mailer->Body = $content;
		$this->mailer->AltBody = strip_tags($contenu['corps']);

	}
	public function init(){

		$this->mailer->IsHTML(true);
		$this->mailer->CharSet = 'UTF-8';

		if($this->isSMTP){
			$this->mailer->IsSMTP();
			$this->mailer->Host       = $this->SMTPHost;
		}
		$this->mailer->Sender     = EMAIL_ADDRESS;
		$this->mailer->From       = EMAIL_ADDRESS;
		$this->mailer->FromName   = EMAIL_NAME;
		$this->mailer->SingleTo = true;
	}

	public function send($destinataires){
		foreach ((array) $destinataires as $dest) {
			$this->mailer->AddAddress($dest);
		}
		return $this->mailer->Send();
	}

}