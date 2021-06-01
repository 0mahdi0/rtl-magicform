<h1><?php esc_html_e("License Activation", "magicform") ?></h1>
<?php 
$demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
if($demo_control !== "demo"){
require_once(MAGICFORM_PATH."/admin/views/dashboard/license.php");
}