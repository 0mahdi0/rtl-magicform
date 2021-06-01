<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    MagicForm
 * @subpackage MagicForm/includes
 * @author     MagicLabs
 */
class MagicForm_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		if (get_option('magic_form')) {
			return;
		}
		$emailSystem = array(
			"selectedSystem" => "smtp"
		);
		add_option("magicform_email_system", json_encode($emailSystem));

		/**
		 * Create Database Tables
		 * There are two tables that we use.
		 * %prefix%_magicform_forms for forms
		 * %prefix%_magicform_submissions for submissions
		 */
		
		require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
		
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'magicform_forms';
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
            id int(11) NOT NULL AUTO_INCREMENT,
            form_name varchar(150) NOT NULL,
            create_date datetime NOT NULL,
            form_data mediumtext NULL,
            preview_form_data mediumtext NULL,
            short_code varchar(100) NOT NULL,
            status int(1) NOT NULL,
            views int(11) NOT NULL,
            owner_id mediumint(9) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);
        
        $fieldTypeSql = "SHOW FIELDS FROM ". $table_name ." WHERE Field ='form_data'";
        $result = $wpdb->get_results($fieldTypeSql)[0];
        if($result->Type != "mediumtext"){
            $sql = "ALTER TABLE ". $table_name ." MODIFY COLUMN 
            `preview_form_data` MEDIUMTEXT , MODIFY COLUMN `form_data` MEDIUMTEXT";
            $wpdb->get_results($sql);
        }

		$charset_collate = $wpdb->get_charset_collate();
        $submission_table_name = $wpdb->prefix . 'magicform_submissions';
        
		$sql = "CREATE TABLE IF NOT EXISTS ".$submission_table_name." (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            form_id mediumint(9) NOT NULL,
            create_date datetime NOT NULL,
            data text NOT NULL,
            ip varchar(50) NULL,
            user_agent varchar(255) NULL,
            os varchar(50) NULL,
            browser varchar(50) NULL,
            device varchar(50) NULL,
            user_id int(11) NULL,
            user_username varchar(50) NULL,
            user_email varchar(200) NULL,
            gdpr int(1) NULL,
            page_title varchar(500) NOT NULL,
            page_url varchar (500) NOT NULL,
            read_status int(1) NOT NULL,
            payment_status int(1) NULL,
            total_amount varchar(200) NULL,
            payment_error varchar(500) NULL, 
            PRIMARY KEY (id)
        ) $charset_collate;";
		dbDelta($sql);

        $fieldTypeSql = "SHOW FIELDS FROM ". $submission_table_name ." WHERE Field ='ip'";
        $result = $wpdb->get_results($fieldTypeSql)[0];
        if($result->Type != "varchar(50)"){
            $sql = "ALTER TABLE ". $submission_table_name ." MODIFY COLUMN 
            `ip` varchar(50)";
            $wpdb->get_results($sql);
        }

        $payment_fields = "SHOW FIELDS FROM ". $submission_table_name ."  LIKE 'id'";
        $result = $wpdb->get_results($payment_fields, ARRAY_A);
        if(count($result)>0){
            $payment_sql = "ALTER TABLE ". $submission_table_name ."
            ADD payment_status int(1) NULL,
            ADD total_amount varchar(200) NULL,
            ADD payment_error varchar(500) NULL";
            $wpdb->get_results($payment_sql);
        }

		$charset_collate = $wpdb->get_charset_collate();
		$notifications_table_name = $wpdb->prefix . 'magicform_notifications';
		$sql = "CREATE TABLE IF NOT EXISTS ".$notifications_table_name." (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            form_id mediumint(9) NOT NULL,
            create_date datetime NOT NULL,
            title varchar(255) NOT NULL,
            data text NOT NULL,
            read_status int(1) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);
        
        $charset_collate = $wpdb->get_charset_collate();
		$products_table_name = $wpdb->prefix . 'magicform_products';
		$sql = "CREATE TABLE IF NOT EXISTS ".$products_table_name." (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            product_name varchar(255) NOT NULL,
            product text NOT NULL,
            status int(1) NOT NULL,
            create_date datetime NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
		dbDelta($sql);
	}
}
