<?php

$data["form_id"] = intval($formId);
$data["data"] = json_encode($formData);
$data['gdpr'] = (isset($formData['mf-gdpr']) && $formData['mf-gdpr'] == "on") ? 1 : 0;
$data["create_date"] = date("Y-m-d H:i:s");
$data["ip"] = $action->payload->logIp ? magicform_getUserIpAddr() : null;
$user_agent = magicform_getBrowser(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
if ($action->payload->logUserAgent)
  $data["user_agent"] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
else
  $data['user_agent'] = null;
$data['browser'] = $user_agent['name'];
$data['os'] = $user_agent['platform'];
$data['device'] = magicform_checkDevice();
$current_user = wp_get_current_user();

if (isset($action->payload->logRegisteredUser)) {
  $data["user_username"] = esc_html($current_user->user_login);
  $data["user_id"] = esc_html($current_user->ID);
  $data["user_email"] = esc_html($current_user->user_email);
} else {
  $data["user_username"] = null;
  $data["user_id"] = null;
  $data["user_email"] = null;
}

$data["page_title"] = $pageTitle;
$data['page_url'] = $pageUrl;
$data["read_status"] = 0;
$this->wpdb->insert($this->submissions_tablename, $data);
if ($this->wpdb->insert_id > 0) {
  $submission_id = $this->wpdb->insert_id;
  return true;
}
return false;
