<?php
if (!function_exists("magicform_sendgridUpdateList")) {
    /**
     * Sendgrid get contact list
     *
     * @param object $payload
     * @param object $formData
     * @param object $settings
     * @return void
     */
    function magicform_sendgridUpdateList($payload, $formData, $settings)
    {
        $fields = array();
        $fields = magicform_mail_list_fields_parse($formData, $payload);

        $apikey = $settings->apikey;
        $url = "https://api.sendgrid.com/v3/marketing/contacts";
        $data = array(
            "list_ids" => array($payload->mailListId),
            "contacts" => array(
                array(
                    "email" =>  $fields['email'],
                    "first_name" =>  $fields['firstName'],
                    "last_name" =>  $fields['lastName']
                )
            )
        );

        $data_string = json_encode($data);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
                'Authorization: Bearer ' . $apikey
            )
        );
        $result = curl_exec($ch);
        return true;
    }
}

$sendGridSettings = json_decode(get_option("magicform_sendgrid_settings"));
magicform_sendgridUpdateList($action->payload, $formData, $sendGridSettings);
