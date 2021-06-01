<h1><?php esc_html_e("Email Settings", "magicform") ?></h1>
<div class="mf-result"></div>
<?php
	$emailSystem = json_decode(get_option("magicform_email_system"));
 ?>

<div class="mf-form-group mf-select-email-system-container">
	<label><?php esc_html_e("Select E-Mail System", "magicform") ?>:</label>
	<label class="radio-inline">
		<input type="radio" <?php echo (isset($emailSystem) && $emailSystem->selectedSystem == "smtp") ? "checked" : "" ?> value="smtp" class="mf-select-email-system" name="mf-email-system" id="smtp" />
		<?php esc_html_e("SMTP", "magicform") ?>
	</label>
	<label class="radio-inline">
		<input type="radio" <?php echo (isset($emailSystem) && $emailSystem->selectedSystem == "sendgrid") ? "checked" : "" ?> value="sendgrid" class="mf-select-email-system" name="mf-email-system" id="sendgrid" />
		<?php esc_html_e("SendGrid", "magicform") ?>
	</label>
	<label class="radio-inline">
		<input type="radio" <?php echo (isset($emailSystem) && $emailSystem->selectedSystem == "mailgun") ? "checked" : "" ?> value="mailgun" class="mf-select-email-system" name="mf-email-system" id="mailgun" />
		<?php esc_html_e("Mailgun", "magicform") ?>
	</label>
</div>

<div id="mf-smtp" style="display:<?php echo ($emailSystem->selectedSystem == "smtp") ? "block" : "none" ?>">
	<?php 
	$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$emailSettings = json_decode(get_option("magicform_email_settings"));
	}
	 ?>
	<h4><?php esc_html_e("Smtp Settings", "magicform"); ?></h4>
	<form name="mf-email-settings-form" action-name="magicform_save_email_settings">
		<input type="hidden" name="emailSystem" value="smtp">
		<div class="form-group row">
			<label for="mf-smtp" class="col-sm-12 col-form-label"><?php esc_html_e("Server Address", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="text" class="mf-admin-form-control" name="smtpAddress" value="<?php echo esc_attr(isset($emailSettings) ? $emailSettings->smtpAddress : ""); ?>" id="mf-smtp" placeholder="smtp.mail.com">
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-email" class="col-sm-12 col-form-label"><?php esc_html_e("Email Address", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="email" class="mf-admin-form-control" name="smtpEmail" value="<?php echo esc_attr(isset($emailSettings) ? $emailSettings->smtpEmail : ""); ?>" id="mf-email" placeholder="example@mail.com">
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-password" class="col-sm-12 col-form-label"><?php esc_html_e("Password", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="password" class="mf-admin-form-control" name="smtpPassword" value="<?php echo esc_attr(magicform_password_view(isset($emailSettings) ? $emailSettings->smtpPassword : "")); ?>" id="mf-password" placeholder="<?php esc_attr_e("Password", "magicform") ?>">
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-number" class="col-sm-12 col-form-label"><?php esc_html_e("Port", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="number" class="mf-admin-form-control" name="smtpPort" value="<?php echo esc_attr(isset($emailSettings) ? $emailSettings->smtpPort : ""); ?>" id="mf-number" placeholder="<?php echo esc_attr_e("Port", "magicform") ?>">
				<small class="form-text text-muted"><?php esc_html_e("usually 587 (alternatives: 465 and 25)", "magicform"); ?></small>
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-number" class="col-sm-12 col-form-label"><?php esc_html_e("Encryption", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<div class="mf-custom-control mf-custom-control-inline mf-custom-radio">
					<input class="mf-custom-control-input" <?php echo (isset($emailSettings) && (isset($emailSettings->encryption) && $emailSettings->encryption == "")) ? "checked" : "" ?> type="radio" value="" name="encryption" id="mf-none">
					<label class="mf-custom-control-label" for="mf-none"><?php esc_html_e("None", "magicform"); ?></label>
				</div>
				<div class="mf-custom-control mf-custom-control-inline mf-custom-radio">
					<input class="mf-custom-control-input" <?php echo (isset($emailSettings) && ($emailSettings->sslRequired == 1 || (isset($emailSettings->encryption) && $emailSettings->encryption == "ssl"))) ? "checked" : "" ?> type="radio" value="ssl" name="encryption" id="mf-ssl">
					<label class="mf-custom-control-label" for="mf-ssl"><?php esc_html_e("SSL", "magicform"); ?></label>
				</div>
				<div class="mf-custom-control mf-custom-control-inline mf-custom-radio">
					<input class="mf-custom-control-input" <?php echo (isset($emailSettings) && (isset($emailSettings->encryption) && $emailSettings->encryption == "tls")) ? "checked" : "" ?> type="radio" value="tls" name="encryption" id="mf-tls">
					<label class="mf-custom-control-label" for="mf-tls"><?php esc_html_e("TLS", "magicform"); ?></label>
				</div>
			</div>
		</div>
	</form>
	<button form-name="mf-email-settings-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
		<i class="far fa-check-circle"></i>
		<?php esc_html_e("Save Settings", "magicform"); ?>
	</button>
</div>

<div id="mf-sendgrid" style="display:<?php echo (isset($emailSystem) && $emailSystem->selectedSystem == "sendgrid") ? "block" : "none" ?>">
	<?php 
	$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$sendgriSettings = json_decode(get_option("magicform_sendgrid_settings"));
	} ?>
	<h4><?php esc_html_e("SendGrid Settings", "magicform"); ?></h4>
	<form name="mf-sendgrid-settings-form" action-name="magicform_save_sendgrid_settings">
		<input type="hidden" name="emailSystem" value="sendgrid">
		<div class="form-group row">
			<label for="mf-smtp" class="col-sm-12 col-form-label"><?php esc_html_e("SendGrid Api Key", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="text" class="mf-admin-form-control" name="apikey" value="<?php echo magicform_password_view(isset($sendgriSettings) ? esc_attr($sendgriSettings->apikey) : ""); ?>" id="mf-sengrid-api-key" placeholder="<?php esc_attr_e("SenGrid Api Key", "magicform") ?>">
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-smtp" class="col-sm-12 col-form-label"><?php esc_html_e("Verify From Mail Address", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="text" class="mf-admin-form-control" name="verifymailaddress" value="<?php echo isset($sendgriSettings) ? esc_attr($sendgriSettings->verifymailaddress) : "" ?>" id="mf-verifyAddress" placeholder="<?php esc_attr_e("Verify From Mail Address", "magicform") ?>">
				<small class="form-text text-muted">
					<?php esc_html_e("Your submission emails are sent via this email address. This mail address is your sendgrid verified address.", "magicform"); ?>
				</small>
			</div>
		</div>
	</form>

	<button form-name="mf-sendgrid-settings-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
		<i class="far fa-check-circle"></i>
		<?php echo esc_html_e("Save Settings", "magicform") ?>
	</button>
</div>

<div id="mf-mailgun" style="display:<?php echo ($emailSystem->selectedSystem == "mailgun") ? "block" : "none" ?>">
	<?php 
	$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
	if($demo_control !== "demo"){
		$mailgunSettings = json_decode(get_option("magicform_mailgun_settings")); 
	}?>
	<h4><?php esc_html_e("Mailgun Settings", "magicform"); ?></h4>
	<form name="mf-mailgun-settings-form" action-name="magicform_save_mailgun_settings">
		<input type="hidden" name="emailSystem" value="mailgun">
		<div class="form-group row">
			<label for="mf-mailgun" class="col-sm-12 col-form-label"><?php esc_html_e("Mailgun Api Key", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="text" class="mf-admin-form-control" name="apikey" value="<?php echo magicform_password_view(isset($mailgunSettings) ? esc_attr($mailgunSettings->apikey) : ""); ?>" id="mf-mailgun-api-key" placeholder="<?php esc_attr_e("Mailgun Api Key", "magicform") ?>">
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-mailgun" class="col-sm-12 col-form-label"><?php esc_html_e("Domain Address", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="text" class="mf-admin-form-control" name="domain" value="<?php echo isset($mailgunSettings) ? esc_attr($mailgunSettings->domain) : "" ?>" id="mf-mailgun-domain" placeholder="<?php esc_attr_e("Domain", "magicform") ?>">
				<small class="form-text text-muted">
					<?php esc_html_e("Follow this link to get a Domain Name from Mailgun: Get a Domain Name.", "magicform"); ?>
				</small>
			</div>
		</div>
		<div class="form-group row">
			<label for="mf-mailgun" class="col-sm-12 col-form-label"><?php esc_html_e("Sender Mail Address", "magicform"); ?>:</label>
			<div class="col-sm-12">
				<input type="text" class="mf-admin-form-control" name="sender" value="<?php echo isset($mailgunSettings) ? esc_attr($mailgunSettings->sender) : "" ?>" id="mf-mailgun-sender" placeholder="<?php esc_attr_e("Sender", "magicform") ?>">
			</div>
		</div>
	</form>

	<button form-name="mf-mailgun-settings-form" class="mf-admin-btn mf-admin-btn-blue mf-btn-settings-save">
		<i class="far fa-check-circle"></i>
		<?php echo esc_html_e("Save Settings", "magicform") ?>
	</button>
</div>

<div class="mf-testemail-section">
	<h4><?php esc_html_e("Email Test", "magicform"); ?></h4>
	<div class="form-group">
		<label><?php esc_html_e("Send To", "magicform"); ?></label>
		<input type="email" class="mf-admin-form-control" id="emailtest-send-to" name="send_to" placeholder="<?php esc_attr_e("e.g john@deo.com", "magicform") ?>">
	</div>
	<div class="mf-mailtest-result"></div>
	<a class="mf-admin-btn mf-admin-btn-ghostblue send-to-test">
		<i class="fas fa-paper-plane"></i>
		<?php esc_html_e("Email Test", "magicform") ?>
	</a>
</div>
<?php require_once(MAGICFORM_PATH . "/admin/views/forms/modals.php"); ?>