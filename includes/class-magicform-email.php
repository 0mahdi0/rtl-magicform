<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require(MAGICFORM_PATH . "/includes/libs/sendgrid-php/sendgrid-php.php");
//require(MAGICFORM_PATH . "/vendor/mailgun/mailgun-php/src/Mailgun.php");
use Mailgun\Mailgun;

/*
 * @package    MagicForm
 * @subpackage MagicForm/includes
 * @author     MagicLabs
 */

class MagicForm_Email
{

	private $type = "smtp";
	private $from = "";
	private $sender = "";
	private $to = array();
	private $subject = "";
	private $body = "";
	private $cc = array();
	private $bcc = array();
	private $replyTo = "";
	private $attachments = array();

	/**
	 * Constructor
	 */
	function __construct()
	{
		$system = json_decode(get_option("magicform_email_system"));
		$type = isset($system) && isset($system->selectedSystem) ? $system->selectedSystem : null;
		if (empty($type)) {
			throw new Exception("Smtp type is not set");
		}
		$this->type = $system->selectedSystem;
	}


	/**
	 * Send email according to type
	 *
	 * @return boolean
	 */
	public function send()
	{
		switch ($this->type) {
			case "smtp":
				return $this->sendwithSmtp();
			case "sendgrid":
				return $this->sendwithSendgrid();
			case "mailgun":
				return $this->sendwithMailgun();
		}
	}

	/**
	 * Send email with Smtp
	 *
	 * @return boolean
	 */
	private function sendwithSmtp()
	{
		WP_Filesystem();
		global $wp_filesystem;
		$emailSettings = json_decode(get_option("magicform_email_settings"));

		$secure = "";
		if (isset($emailSettings->encryption)) {
			$secure = $emailSettings->encryption;
		} else {
			$secure = $emailSettings->sslRequired == "1" ? "ssl" : "";
		}

		try {

			$mail = new PHPMailer(true);
			$mail->SMTPDebug = 0;                      // Enable verbose debug output
			// $mail->SMTPOptions = array(
			// 	'ssl' => array(
			// 		'verify_peer' => false,
			// 		'verify_peer_name' => false,
			// 		'allow_self_signed' => true
			// 	)
			// );
			$mail->isSMTP();                                         // Send using SMTP
			$mail->CharSet = 'UTF-8';
			$mail->Host       = $emailSettings->smtpAddress;         // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                // Enable SMTP authentication
			$mail->Username   = $emailSettings->smtpEmail;           // SMTP username
			$mail->Password   = $emailSettings->smtpPassword;        // SMTP password
			$mail->SMTPSecure = $secure;        					 // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->Port       = $emailSettings->smtpPort;            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
			
			$mail->setFrom($emailSettings->smtpEmail, $this->sender);
			
			if ($this->isEmail($this->replyTo)) {
				$mail->addReplyTo($this->replyTo);
			}

			// Add a recipient
			foreach ($this->to as $address) {
				$mail->addAddress($address);
			}

			if(is_array($this->cc) && count($this->cc)>0){
				foreach($this->cc as $address){
					$mail->addCC($address);
				}
			}
	
			if(is_array($this->bcc) && count($this->bcc)>0){
				foreach($this->bcc as $address){
					$mail->addBCC($address);
				}
			}

			// Attachments
			foreach ($this->attachments as $attachment) {
				switch ($attachment["type"]) {
					case "generatedPdf":
						$mail->addStringAttachment($wp_filesystem->get_contents($attachment["attachment"]), $attachment["filename"]);
						break;
					case "upload":
						$mail->addAttachment($attachment["attachment"],$attachment["filename"]);
						break;
					case "file":
						$mail->addStringAttachment($wp_filesystem->get_contents($attachment["attachment"]),$attachment["filename"]);
						break;
				}
			}

			// Content
			$mail->isHTML(true);
			$mail->Subject = $this->subject;
			$mail->Body    = $this->body;
			$mail->send();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Send Email with SendGrid
	 *
	 * @return void
	 */
	private function sendwithSendgrid()
	{

		WP_Filesystem();
		global $wp_filesystem;

		$sengridSettings = json_decode(get_option("magicform_sendgrid_settings"));
		$email = new \SendGrid\Mail\Mail();
		$email->setFrom($sengridSettings->verifymailaddress,$this->sender);
		$email->setSubject($this->subject);
		// Add a recipient
		foreach ($this->to as $address) {
			$email->addTo($address);
		}
		
		if(is_array($this->cc) && count($this->cc)>0){
			foreach($this->cc as $address){
				$email->addCC($address);
			}
		}

		if(is_array($this->bcc) && count($this->bcc)>0){
			foreach($this->bcc as $address){
				$email->addBCC($address);
			}
		}

		if ($this->isEmail($this->replyTo)) {
			$email->setReplyTo($this->replyTo);
		}

		$email->addContent("text/html", $this->body);

		// Attachments
		foreach ($this->attachments as $attachment) {
			switch ($attachment["type"]) {
				case "generatedPdf":
					$email->addAttachment(
						$wp_filesystem->get_contents($attachment["attachment"]),
						"application/pdf",
						$attachment["filename"],
						"attachment"
					);
					break;
				case "upload":
					$file_encoded = $wp_filesystem->get_contents($attachment["attachment"]);
					$email->addAttachment(
						$file_encoded,
						$attachment["mimetype"],
						$attachment["filename"],
						"attachment"
					);
					break;
				case "file":
					$file_encoded = $wp_filesystem->get_contents($attachment["attachment"]);
					$email->addAttachment(
						$file_encoded,
						$attachment["mimetype"],
						$attachment["filename"],
						"attachment"
					);
					break;
			}
		}
		
		$sendgrid = new \SendGrid($sengridSettings->apikey);
		try {
			$response = $sendgrid->send($email);
			if(strpos($response->statusCode(),"20")===0){
				return true;
			}  else {
				$responseBody = json_decode($response->body());
				if(isset($responseBody->errors) && is_array($responseBody->errors)){
					throw new Exception("Sendgrid: ".$responseBody->errors[0]->message." ".$responseBody->errors[0]->field);
				} else {
					throw new Exception("Sendgrid: An error occurred");
				}
				return false;
			}
		} catch (Exception $e) 
		{
			throw new Exception($e->getMessage());
			return false;
		}
	}

	/**
	 * Send Email with Mailgun
	 *
	 * @return voidtext
	 */

	function sendwithMailgun() {


		# Instantiate the client.
		$mailgunSettings = json_decode(get_option("magicform_mailgun_settings"));
	
		$mgClient =  Mailgun::create($mailgunSettings->apikey);
		# Make the call to the client.
		$domain = $mailgunSettings->domain;
		$params = array(
			'from'    => empty($this->sender)?$mailgunSettings->sender:$this->sender,
			'to'      => implode(",", $this->to),
			'subject' => $this->subject,
			'html'    => $this->body,
			"h:Reply-To" => $this->replyTo
		);

		if(is_array($this->cc) && count($this->cc)>0){
				$params['cc'] = implode(",", $this->cc);
		}

		if(is_array($this->bcc) && count($this->bcc)>0){
			$params['bcc'] = implode(",", $this->bcc);
		}

		$params['attachment'] = array();

		foreach ($this->attachments as $attachment) {
					array_push($params['attachment'],
						array(
							'filePath' => $attachment["attachment"],
							'filename' => $attachment["filename"]
						) 
					);
		}
		return $mgClient->messages()->send($domain, $params);
	}

	/**
	 * Check is email
	 *
	 * @param [type] $email
	 * @return boolean
	 */
	private function isEmail($email)
	{
		if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i', $email)) {
			return false;
		}
		return true;
	}

	/**
	 * Set From
	 *
	 * @param string $from
	 * @return void
	 */
	public function setFrom($from)
	{
		$this->from = $from;
	}

	/**
	 * Set Sender
	 *
	 * @param string $sender
	 * @return void
	 */
	public function setSender($sender)
	{
		$this->sender = $sender;
	}

	/**
	 * Set To
	 *
	 * @param array $to
	 * @return void
	 */
	public function setTo($to)
	{
		$this->setValues($to,"to");
	}

	/**
	 * Set Subjecy
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	/**
	 * Set Body
	 *
	 * @param string $body html
	 * @return void
	 */
	public function setBody($body)
	{
	/*	if(empty($body)){
			$body = "Empty Message";
		}*/
		$bodywithtemplate = $this->template($body);
		$this->body = $bodywithtemplate;
	}

	/**
	 * Set Cc
	 *
	 * @param array $cc
	 * @return void
	 */
	public function setCc($cc = array())
	{
		$this->setValues($cc,"cc");
	}

	/**
	 * Set Bcc
	 *
	 * @param array $bcc
	 * @return void
	 */
	public function setBcc($bcc = array())
	{
		$this->setValues($bcc,"bcc");
	}

	/**
	 * Set Reply To
	 *
	 * @param string $email
	 * @return void
	 */
	public function setReplyTo($email)
	{
		$this->replyTo = $email;
	}

	/**
	 * Add Attachment
	 *
	 * @param binary $attachment
	 * @param string $type
	 * @return void
	 */
	public function addAttachment($attachment, $type = "upload", $filename = "", $mimetype="")
	{
		$attachmentItem = array("type" => $type, "attachment" => $attachment,"mimetype"=>$mimetype);
		if ($filename != "") {
			$attachmentItem["filename"] = $filename;
		}
		$this->attachments[] = $attachmentItem;
	}

	private function setValues($value,$property){
		$parsedValues = array();
		if (is_array($value)) {
			$parsedValues = $value;
		} else {
			if (strpos($value, ",") !== false) {
				$parts = explode(",", $value);
				foreach ($parts as $item) {
					if(magicform_email_validation(trim($item)))
						$parsedValues[] = trim($item);
				}
			} else {
				if(magicform_email_validation(trim($value))){
					$parsedValues[] = trim($value);
				}
			}
		}
		$this->$property = $parsedValues;
	}

	public function template($body){
		ob_start();
		include MAGICFORM_PATH."/admin/views/components/email_template.php";
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

}
