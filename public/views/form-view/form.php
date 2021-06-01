
<?php
$pageCount = count($el->pages);
$isMultiPage = $pageCount > 2;
$formSettings = $el->settings;
$actions = $el->actions;
$formId = $this->formId;
$validationCheck = $this->validationCheck;
$submitType = $this->submitType;
$rules = $this->json->settings->rules;
require("themeStyles.php");
if ($submitType == "preview") : ?>
    <button onClick="window.location.reload();" class="mf-btn mf-reload-btn"><i class="fas fa-redo-alt"></i></button>
<?php endif; ?>

<?php 
    $paymentStatus = false;
    $paymentType = "";
    $paymentSettings = array();
    $currency = "";
    foreach($el->actions as $action) {
        if ($action->type === "stripe" && $action->active) {
            echo '<script src="https://js.stripe.com/v3/"></script>';
            $paymentType = "stripe";
        }
        else if($action->type === "paypal"  &&  $action->active){
            $currency = $action->payload->currency;
            $paymentSettings = json_decode(get_option("magicform_paypal_settings"));
            $paymentType = "paypal";
        }
    }

    if(isset($_GET['session_id']) && $paymentType == "stripe") {
        include MAGICFORM_PATH . "/public/views/form-view/actions/stripePaymentControle.php";
        
        if(!$paymentStatus){
            echo "<div className='alert alert-warning'>
                    <strong>Your payment closed </strong> 
                </div>";
        }
    }else if($paymentType == "paypal"){
        echo '<script src="https://www.paypal.com/sdk/js?client-id='.$paymentSettings->clientId.'&currency='. strtoupper($currency) .'"></script>';
    }
?>
  
<form name="magicform-<?php echo esc_attr($this->formId) ?>" id="magicform-<?php echo esc_attr($this->formId); ?>" class='magicform magicform-<?php echo esc_attr($this->formId); ?> magicform-<?php echo esc_attr($formSettings->theme->name) ?>' style="<?php echo (isset($formSettings->bgColor)) ? "background-color:" . magicform_convertRgba($formSettings->bgColor) : "" ?>" enctype="multipart/form-data" novalidate>
    <?php if (!$this->formLimit && $this->submitType == "original") {  ?>
        <div class="mf-alert mf-alert-warning">
            <strong><?php echo esc_html($formSettings->translate->formLimitMessage); ?></strong>
        </div>
    <?php } ?>
    <?php if ($formSettings->loading->type == "form") : ?>
        <div class="mf-loading-overlay">
            <?php echo magicform_getLoadingIcon($formSettings->loading->icon, $formSettings->loading->iconColor) ?>
            <p><?php echo esc_html($formSettings->loading->text) ?></p>
        </div>
    <?php endif;  ?>

    <?php if (trim($formSettings->title, "") != "" && $formSettings->titleVisible) : ?>
        <h1 class='mf-form-title'><?php echo esc_html($formSettings->title) ?></h1>
    <?php endif; ?>
    <?php if (trim($formSettings->description, "") != "") : ?>
        <p class='mf-form-description'><?php echo esc_html($formSettings->description); ?></p>
    <?php endif; ?>

    <?php include "multipageHeader.php"; ?>

    <?php if ($isMultiPage) magicform_getProgressBar($formSettings->progressBar, "top"); ?>

    <div class='mf-pages'>
        <?php echo ($this->viewForm("page", $el->pages)); ?>
    </div>
    
    <?php
   
        if ($formSettings->csrfToken) wp_nonce_field('submit_magicform_form_nonce', 'magicform_token');
        if ($isMultiPage) magicform_getProgressBar($formSettings->progressBar, "bottom");
       
        $validations = $this->validations;
        $allElements = $this->allElements;
        $pageElements = $this->pageElements;
        
        // Preview page validation 
        if($this->type == 0 ){
            include "validations.php";
            include "conditionalLogic.php";
        }
        else {
            // Validation 
            add_action('wp_footer',  function() use ( $formId, $submitType, $formSettings, $allElements, $pageElements, $validations, $validationCheck, $actions, $paymentStatus) { 
                ob_start();
                include(MAGICFORM_PATH . "/public/views/form-view/validations.php");
                    $validation_content = ob_get_contents();
                ob_end_clean ();
                echo $validation_content;
    
             },1001);  

            // Conditional logic 
            add_action('wp_footer',  function() use ( $formId, $rules) { 
                ob_start();
                include(MAGICFORM_PATH . "/public/views/form-view/conditionalLogic.php");
                    $validation_content = ob_get_contents();
                ob_end_clean ();
                echo $validation_content;
    
             },1002);  
        }

       
        if ($formSettings->customCss != "") magicform_getCustomCss($formSettings->customCss);
    ?>

<?php if( get_option("magicform_purchase_code") == ""):?>
    <a target="_blank" href="https://1.envato.market/M7aVM" class="mf-powered-by">
    <span> <?php esc_html_e("Powered By","magicform")?> </span>
    <img class="mf-poweredby-logo" alt="MagicForm Wordpress Form Builder Plugin" src="<?php echo MAGICFORM_URL .'assets/images/logo_dark-powered-by.svg'?>" /> </a>
<?php endif;?>

</form>

     <!-- Set up a container element for the button -->
     <div id="paypal-button-container"></div>

<?php if (!$this->formLimit && $this->submitType == "original") : ?>
    <script>
        jQuery("#magicform-<?php echo intval($this->formId) ?> :input").prop("disabled", true);
        
    </script>
<?php endif; ?>


