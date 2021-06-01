<?php
if (!function_exists("magicform_updateMailchimpList")) {
    /**
     * Mailchimp get contact list
     *
     * @param object $payload
     * @param object $formData
     * @param object $settings
     * @return void
     */
    function magicform_updateMailchimpList($payload, $formData, $settings)
    {
        $fields = array();
        $fields = magicform_mail_list_fields_parse($formData, $payload);
        
        $apikey = $settings->apikey;
        $dc = [];
        preg_match('/(?<=\-).*/', $apikey, $dc);
        $url = "https://" . $dc[0] . ".api.mailchimp.com/3.0/lists/$payload->mailListId/members";
        $merge_fields = array("FNAME"=> $fields["firstName"],"LNAME"=> $fields["lastName"]);
        $data = array("email_address" => $fields["email"], "email_type" => "text", "status" => "subscribed", "merge_fields" => $merge_fields);
        print_r($data);
        $data_string = json_encode($data);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: apikey ' . $apikey
            )
        );

        $result = curl_exec($ch);
        print_r($result);
        if ($result->status == "subscribed") {
            return true;
        }
        return false;
    }
}
$mailChimpSettings = json_decode(get_option("magicform_mailchimp_settings"));
magicform_updateMailchimpList($action->payload, $formData, $mailChimpSettings);
