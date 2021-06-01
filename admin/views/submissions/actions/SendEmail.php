<?php

include('magicform-text-parser.php');
WP_Filesystem();
global $wp_filesystem;
$subject = magicform_textParser($action->payload->subject, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);
$message = magicform_textParser($action->payload->message, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);
$replyTo = magicform_textParser($action->payload->replyTo, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);
$to = magicform_textParser($action->payload->to, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);
$cc = magicform_textParser($action->payload->cc, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl,$submission_id);
$bcc = magicform_textParser($action->payload->bcc, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);

try {
    $email = new MagicForm_Email();
    $email->setTo($to);
    $email->setSender($action->payload->sender);
    $email->setSubject($subject);
    $email->setBody($message);
    $email->setReplyTo($replyTo);

    if(!empty($cc)){
        $email->setCc($cc);
    }

    if(!empty($bcc)){
        $email->setBcc($bcc);
    }


    // Add upload file 
    if(count($action->payload->files) > 0){
        foreach($action->payload->files as $file){
            $email->addAttachment($file->url,"file", $file->fileName, $file->mime);
        }
    }

    // Attach Pdf
    if (isset($action->payload->attachPdf) && $action->payload->attachPdf && !empty($generatedPdfFile)) {
        $email->addAttachment($generatedPdfFile, "generatedPdf", basename($generatedPdfFile));
    }

    // Attach Uploaded Files
    if ($action->payload->attachUploadedFiles) {
        foreach ($this->uploadedFiles as $file) {
            $email->addAttachment($file["file"], "upload", basename($file["file"]), $file["type"]);
        }
    }

    $email->send();
} catch (Exception $e) {
    return false;
}
