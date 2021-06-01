<div class="mf-mainContainer mf-mainContainerAdmin mainContainerSettings">
	<div class="mf-contentContainer">
		<div class="mf-header">
			<div class="mf-header-left">
				<h1><a class="mf-logo" href="<?php echo esc_url("?page=magicform_admin") ?>">
						<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/logo_square.svg"); ?>" />
						<?php esc_html_e("Settings", "magicform") ?>
					</a>
				</h1>
			</div>
			<div class='mf-header-center'></div>
			<?php require_once(MAGICFORM_PATH . "/admin/views/components/header-right.php"); ?>
		</div>

		<div class="mf-settings">
			<div class="mf-settings-left">
				<div class="mf-settings-left-inner">
					<ul class="mf-settings-nav">
						<?php $subpage = isset($_GET["sub_page"]) ? sanitize_key($_GET["sub_page"]) : null; ?>
						<li class="<?php echo ($subpage == "license") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "license") ?>">
								<i class="far fa-id-badge"></i>
								<?php esc_html_e("License");?>
								<?php get_option("magicform_purchase_code") != "" ? 
								"<span class='mf-badge mf-badge-pro'>".esc_html_e("Pro", "magicform")."</span>"
								:"<span class='mf-badge'>".esc_html_e("Free", "magicform")."</span>";  
								?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "email" || $subpage == null) ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "email") ?>">
								<i class="far fa-envelope"></i>
								<?php esc_html_e("Email", "magicform"); ?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "sendgrid") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "sendgrid") ?>">
								<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/sendgrid.svg"); ?>" />
								<?php esc_html_e("SendGrid", "magicform"); ?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "mailchimp") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "mailchimp") ?>">
								<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/mailchimp.svg"); ?>" />
								<?php esc_html_e("MailChimp", "magicform"); ?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "getresponse") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "getresponse") ?>">
								<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/getresponse.svg"); ?>" />
								<?php esc_html_e("GetResponse", "magicform"); ?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "google") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "google") ?>">
								<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/googlemaps.svg"); ?>" />
								<?php esc_html_e("Google Maps", "magicform"); ?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "recaptcha") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "recaptcha") ?>">
								<img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/recaptcha.svg"); ?>" />
								<?php esc_html_e("Recaptcha", "magicform"); ?>
							</a>
						</li>
						<li class="<?php echo ($subpage == "stripe") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "stripe") ?>">
							<img style="width:45px;" src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/stripe.svg"); ?>" />
							</a>
						</li>
						<li class="<?php echo ($subpage == "paypal") ? "active" : "" ?>">
							<a href="<?php printf(esc_url("?page=magicform_settings&sub_page=%s"), "paypal") ?>">
							<img style="width:45px;" src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/paypal.svg"); ?>" />
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="mf-settings-right">
				<?php
				switch ($subpage) {
					case "google":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/google-settings.php");
						break;
					case "recaptcha":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/recaptcha-settings.php");
						break;
					case "mailchimp":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/mailchimp-settings.php");
						break;
					case "getresponse":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/getresponse-settings.php");
						break;
					case "sendgrid":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/sendgrid-settings.php");
						break;
					case "license":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/license.php");
						break;
					case "stripe":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/stripe.php");
						break;
					case "paypal":
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/paypal.php");
						break;
					default:
						require_once(MAGICFORM_PATH . "/admin/views/settings/sub-pages/email-settings.php");
				}
				?>
			</div>
		</div>
	</div>
</div>