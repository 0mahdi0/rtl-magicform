<?php 

if (!function_exists("magicform_getResponeUpdateList")) {
/**
 * Get getreponse contact list
 *
 * @param object $payload
 * @param object $formData
 * @param object $settings
 * @return 
 */
    function magicform_getResponeUpdateList($payload, $formData, $settings) 
    {
    $fields = array();
    $fields = magicform_mail_list_fields_parse($formData, $payload);
    
    $apikey = $settings->apikey;
    $url = "https://api.getresponse.com/v3/contacts";
    $data = array(
        "email" =>$fields["email"], 
            "campaign" => array(
                "campaignId" => $payload->mailListId
            )
        );      
                                                                
    $data_string = json_encode($data);                                                                                                                                                                             
    $ch = curl_init($url);         

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                                                              
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string),
        'X-Auth-Token: api-key '.$apikey       
        )                                                                 
    );                                                                                                                   
                                                                                        
    $result = curl_exec($ch);
    return true;
    }   
}
$getresponseSettings = json_decode(get_option("magicform_getresponse_settings"));
magicform_getResponeUpdateList($action->payload, $formData, $getresponseSettings);
