<?php
include('magicform-text-parser.php');
WP_Filesystem();
global $wp_filesystem;

$subject = magicform_textParser($action->payload->subject, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl,$submission_id);
$message = magicform_textParser($action->payload->message, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);
$replyTo = magicform_textParser($action->payload->replyTo, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);
$to = magicform_textParser($action->payload->to, $formData, $this->allElements, $formSettings, $pageTitle, $pageUrl, $submission_id);

 try {
    $email = new MagicForm_Email();
    $email->setTo($to);
    $email->setSender($action->payload->sender);
    $email->setSubject($subject);
    $email->setBody($message);
    $email->setReplyTo($replyTo);

    // Add upload file 
    if(count($action->payload->files) > 0){
        foreach($action->payload->files as $file){
            $email->addAttachment($file->url,"file", $file->fileName, $file->mime);
        }
    }

    // Add generated pdf
    if (isset($action->payload->attachPdf) && $action->payload->attachPdf && !empty($generatedPdfFile)) {
        $email->addAttachment($generatedPdfFile, "generatedPdf", basename($generatedPdfFile));
    }
    $email->send();
} catch (Exception $e) {
    //magicform_add_notification($formId, "Auto Responder Problem",$e->getMessage());
    return false;
}