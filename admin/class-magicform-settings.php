<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    MagicForm
 * @subpackage MagicForm/admin
 * @author     MagicLabs
 */

class MagicForm_Settings
{

    // Plugin Name
    private $plugin_name;

    // Plugin Version
    private $version;

    // Table name for forms table
    private $forms_tablename;

    // Table name for submissions table
    private $submissions_tablename;

    // Global wpdb object
    private $wpdb;

    /**
     * Constructor
     *
     * @param String $plugin_name
     * @param String $version
     * @param String $forms_tablename
     * @param String $submissions_tablename
     */
    public function __construct($plugin_name, $version, $forms_tablename, $submissions_tablename)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->forms_tablename = $forms_tablename;
        $this->submissions_tablename = $submissions_tablename;
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Set Admin Pages
     *
     * @return void
     */
    public function setPages()
    {
        /**
         * Dashboard Page
         * Subpage
         */
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        add_submenu_page("magicform_admin", esc_html__("Settings","magicform"), esc_html__("Settings","magicform"),  $demo_control !== "demo"?"manage_options":"edit_posts", "magicform_settings", array($this, "view"));
    }

    /**
     * View
     */
    public function view()
    {
        return require_once(MAGICFORM_PATH . "/admin/views/settings/settings.php");
    }

    /**
     * Table name
     */
    function get_table_name()
    {
        wp_send_json_success(array("table_name" => $this->forms_tablename));
    }

    /**
     * Save Email Settings
     */
    function save_email_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $data = $_POST;
        $smtpAddress = sanitize_text_field($data["smtpAddress"]);
        $smtpEmail = sanitize_email($data["smtpEmail"]);
        $smtpPassword = sanitize_text_field($data["smtpPassword"]);
        $smtpPort = intval($data["smtpPort"]);
        $sslRequired = intval($data["sslRequired"]) == "1" ? 1 : 0;
        $encryption = sanitize_key($data["encryption"]);
        $system = sanitize_key($_POST['emailSystem']);
        // Validasyon
        if (empty($smtpAddress)) {
            wp_send_json_error(esc_html__("Smtp host is required", "magicform"));
            return false;
        }
        if (empty($smtpEmail)) {
            wp_send_json_error(esc_html__("Email is required", "magicform"));
            return false;
        }
        if (empty($smtpPassword)) {
            wp_send_json_error(esc_html__("Password is required", "magicform"));
            return false;
        }
        if (empty($smtpPort)) {
            wp_send_json_error(esc_html__("Smtp port is required", "magicform"));
            return false;
        }

        $data = array(
            "smtpAddress" => $smtpAddress,
            "smtpEmail" => $smtpEmail,
            "smtpPassword" => $smtpPassword,
            "smtpPort" => $smtpPort,
            "sslRequired" => $sslRequired,
            "encryption"=>$encryption
        );

        $emailSystem = array(
            "selectedSystem" => $system
        );

        update_option('magicform_email_system', json_encode($emailSystem));
        update_option('magicform_email_settings', json_encode($data));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

    /**
     * Save Google Settings
     */
    function save_google_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $data = $_POST;
        $googleApiKey = sanitize_text_field($data['googleApiKey']);
        if (empty($googleApiKey)) {
            wp_send_json_error(esc_html__("Google api key is required", "magicform"));
            return false;
        }

        $googleSettings = array(
            "googleApiKey" => $googleApiKey
        );

        update_option('magicform_google_settings', json_encode($googleSettings));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

    /**
     * Save Recaptcha settings
     */
    function save_recaptcha_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $data = $_POST;
        $recaptchaSitev2    = sanitize_text_field($data['recaptchaSitev2']);
        $recaptchaSecretv2  = sanitize_text_field($data['recaptchaSecretv2']);
        $recaptchaSitev3    = sanitize_text_field($data['recaptchaSitev3']);
        $recaptchaSecretv3  = sanitize_text_field($data['recaptchaSecretv3']);

        $recaptchaSettings = array(
            "recaptchaSitev2"   => $recaptchaSitev2,
            "recaptchaSecretv2" => $recaptchaSecretv2,
            "recaptchaSitev3"   => $recaptchaSitev3,
            "recaptchaSecretv3" => $recaptchaSecretv3
        );

        update_option('magicform_recaptcha_settings', json_encode($recaptchaSettings));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

    /**
     * Save Sendgrid settings
     */
    function save_sendgrid_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $apikey             = sanitize_text_field($_POST['apikey']);
        $verifyMailAddress  = sanitize_text_field($_POST['verifymailaddress']);
        $system             = sanitize_text_field($_POST['emailSystem']);
        if (empty($apikey)) {
            wp_send_json_error(esc_html__('SendGrid api key is required', 'magicform'));
            return false;
        }
        if (empty($verifyMailAddress)) {
            wp_send_json_error(esc_html__('SendGrid verify from mail address is required', 'magicform'));
            return false;
        }

        $sendgridSettings = array(
            "verifymailaddress" => $verifyMailAddress,
            "apikey" => $apikey
        );

        $emailSystem = array(
            "selectedSystem" => $system
        );

        update_option('magicform_email_system', json_encode($emailSystem));
        update_option('magicform_sendgrid_settings', json_encode($sendgridSettings));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

    /**
     * Save sendgrid api key
     */
    function save_sendgrid_api_key()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $apikey = sanitize_text_field($_POST['apikey']);
        if (empty($apikey)) {
            wp_send_json_error(esc_html__('Sengrid api key is required', 'magicform'));
            return false;
        }

        $settings = json_decode(get_option("magicform_sendgrid_settings"));
        $sengrid = array(
            "verifymailaddress" => $settings->verifymailaddress,
            "apikey" => $apikey
        );
      
        update_option('magicform_sendgrid_settings', json_encode($sengrid));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

   /**
     * Save stripe api key
     */
    function save_stripe_api_key()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $secretKey = sanitize_text_field($_POST['secretKey']);
        $publishableKey = sanitize_text_field($_POST['publishableKey']);
        if (empty($secretKey)) {
            wp_send_json_error(esc_html__('Stripe secret key is required', 'magicform'));
            return false;
        }

        if(empty($publishableKey)){
            wp_send_json_error(esc_html__('Stripe publisable key is required', 'magicform'));
            return false;
        }

        $stripe = array(
            "secretKey" => $secretKey,
            "publishableKey" => $publishableKey
        );
      
        update_option('magicform_stripe_settings', json_encode($stripe));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

     /**
     * Save paypal api key
     */
    function save_paypal_api_key()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $clientSecret = sanitize_text_field($_POST['clientSecret']);
        $clientId = sanitize_text_field($_POST['clientId']);
        $paymentType = sanitize_text_field($_POST['paymentType']);

        if (empty($clientSecret)) {
            wp_send_json_error(esc_html__('Paypal client secret key is required', 'magicform'));
            return false;
        }

        if(empty($clientId)){
            wp_send_json_error(esc_html__('Paypal client id is required', 'magicform'));
            return false;
        }

        if(empty($paymentType)){
            wp_send_json_error(esc_html__('Paypal payment type is required', 'magicform'));
            return false;
        }

        $paypal = array(
            "clientSecret" => $clientSecret,
            "clientId" => $clientId,
            "paymentType" => $paymentType
        );
      
        update_option('magicform_paypal_settings', json_encode($paypal));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }


    /**
     * Save Mailchimp settings
     */
    function save_mailcimp_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $data = $_POST;
        $apiKey = sanitize_text_field($data['apikey']);
        if (empty($apiKey)) {
            wp_send_json_error(esc_html__('MailChimp api key is required', 'magicform'));
            return false;
        }

        $mailChimp = array(
            "apikey" => $apiKey
        );

        update_option('magicform_email_system', json_encode($emailSystem));
        update_option("magicform_mailchimp_settings", json_encode($mailChimp));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }


    /**
     * Save Mailchimp settings
     */
    function save_mailgun_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        } 

        $data = $_POST;
        $apiKey = sanitize_text_field($data['apikey']);
        $domain = sanitize_text_field($data['domain']);
        $sender = sanitize_text_field($data['sender']);
        $system = sanitize_text_field($_POST['emailSystem']);

        if (empty($apiKey)) {
            wp_send_json_error(esc_html__('Mailgun api key is required', 'magicform'));
            return false;
        }

        if (empty($domain)) {
            wp_send_json_error(esc_html__('Mailgun domain is required', 'magicform'));
            return false;
        }

        if (empty($sender)) {
            wp_send_json_error(esc_html__('Mailgun sender mail address is required', 'magicform'));
            return false;
        }

        $mailgun = array(
            "apikey" => $apiKey,
            "domain" => $domain,
            "sender" => $sender
        );

        $emailSystem = array(
            "selectedSystem" => $system
        );

        update_option('magicform_email_system', json_encode($emailSystem));
        update_option("magicform_mailgun_settings", json_encode($mailgun));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

    /**
     * Save Get Response settings
     */
    function save_getresponse_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(esc_html__("Settings change disabled in demo.", "magicform"));
            return false;
        }      

        $data = $_POST;
        $apiKey = sanitize_text_field($data['apikey']);
        if (empty($apiKey)) {
            wp_send_json_error(esc_html__('GetResponse api key is required', 'magicform'));
            return false;
        }

        $getResponse = array(
            "apikey" => $apiKey
        );

        update_option("magicform_getresponse_settings", json_encode($getResponse));
        wp_send_json_success(esc_html__("Settings Saved.", "magicform"));
    }

    /**
     * Get Mailchimp contact list
     */
    function get_mailchimp_list()
    {
        $settings = json_decode(get_option("magicform_mailchimp_settings"));
        $apikey = $settings->apikey;
        $dc = [];
        preg_match('/(?<=\-).*/', $apikey, $dc);
        $url = 'https://' . $dc[0] . '.api.mailchimp.com/3.0/lists/?apikey=' . $apikey;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $result = json_decode(curl_exec($ch));

        if ($result->status || $result == null) {
            wp_send_json_error($result, 400);
        } else {
            $response = [];
            foreach ($result->lists as $item) {
                array_push(
                    $response,
                    array(
                        "id" => $item->id,
                        "name" => esc_html($item->name)
                    )
                );
            }
            wp_send_json_success($response);
        }
    }

    /**
     * Get Sendgrid contact list
     */
    function get_sendgrid_list()
    {
        $settings = json_decode(get_option("magicform_sendgrid_settings"));
        $apikey = $settings->apikey;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/marketing/lists?page_size=100');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apikey
            )
        );

        $result = json_decode(curl_exec($ch));
        if ($result->errors) {
            wp_send_json_error($result, 400);
        } else
            wp_send_json_success($result->result);
    }

    /**
     * Getresponse contact list
     */
    function get_getresponse_list()
    {
        $settings = json_decode(get_option("magicform_getresponse_settings"));
        $apikey = $settings->apikey;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.getresponse.com/v3/campaigns/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'X-Auth-Token: api-key ' . $apikey
            )
        );

        $result = json_decode(curl_exec($ch));
        if ($result->httpStatus) {
            wp_send_json_error($result, 400);
        } else
            wp_send_json_success($result);
    }

    /**
     * Get google map settings
     */
    function get_googlemap_settings()
    {
        wp_send_json_success(json_decode(get_option("magicform_google_settings")));
    }

    /**
     * Get recaptcha settings
     */
    function get_recaptcha_settings()
    {
        $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
        if($demo_control == "demo"){
            wp_send_json_error(404);
            return false;
        } 

        if (get_option("magicform_recaptcha_settings") == "")
            wp_send_json_error(404);
        else
            wp_send_json_success(json_decode(get_option("magicform_recaptcha_settings")));
    }


    /**
     * Post constants
     */
    function magicform_post_constants () {
        $allUsers = get_users();
        $allCategories = get_categories(
            array( 
                'hide_empty' => false
            )
        );
        $users = array();
        $categories = array();

        foreach($allUsers as $value)
        {
            $user['id'] = $value->data->ID;
            $user['name'] = $value->data->user_login;
            array_push($users, $user);
        }

        foreach($allCategories as $value) {
            $category['id'] = $value->cat_ID;
            $category['name'] = $value->name;
            array_push($categories, $category);
        }

        $data['categories'] = $categories;
        $data['authors'] = $users;
        
        wp_send_json_success($data);
    }

     /**
     *  Email test
     */

    function magicform_smtp_test() {

        $data = $_POST;
        if(empty($data['email'])){
            wp_send_json_error(esc_html__('Email is required', 'magicform'));
            return false;   
        }

        if(!magicform_email_validation($data['email'])){
            wp_send_json_error(esc_html__('Email is not valid', 'magicform'));
            return false;   
        }

        $system = json_decode(get_option("magicform_email_system"));
		$type = isset($system) && isset($system->selectedSystem) ? $system->selectedSystem : null;
        
        $type = $system->selectedSystem;
        $sender = "";
        switch ($type) {
			case "smtp":
                $emailSettings = json_decode(get_option("magicform_email_settings"));
                $sender = $emailSettings->smtpEmail;
            break;
            case "sendgrid":
                $emailSettings = json_decode(get_option("magicform_sendgrid_settings"));
                $sender = $emailSettings->verifymailaddress;
            break;
            case "mailgun":
                $emailSettings = json_decode(get_option("magicform_mailgun_settings"));
                $sender = $emailSettings->sender;
            break;
            default:
                wp_send_json_error(esc_html__('Email system is null please check your email system', 'magicform'));
                return false;
            break;
		}

        try {
            $email = new MagicForm_Email();
            $email->setTo(esc_html($_POST['email']));
            $email->setSender($sender);
            $email->setSubject("Magicform Test SMTP");
            $email->setBody("This is Magicform test e-mail. ");
            $email->send();
            wp_send_json_success(esc_html__('Email sending is succes', 'magicform'));
        }catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
}
