<?php
$payload = $action->payload;
$event = addslashes($payload->event);
$eventAction = addslashes($payload->eventAction);
$eventLabel = addslashes($payload->eventLabel);
$eventValue = isset($payload->eventValue) ? intval($payload->eventValue) : 0;
if (isset($payload->type) && $payload->type != "") {
    switch ($payload->type) {
        case "tagmanager":
            echo "if(typeof dataLayer != 'undefined') {" .
                "dataLayer.push({" .
                "'event': '" . $event . "'," .
                "'action': '" . $eventAction . "'," .
                "'label': '" . $eventLabel . "'," .
                "'value': '" . $eventValue . "' });" .
                "}";
            break;
        case "analyticsjs":
            echo "if(typeof ga != 'undefined') {" .
                "ga('send', 'event', '" . $event . "', '" . $eventAction . "', '" . $eventLabel . "', ".$eventValue.");" .
                "}";
            break;
        case "gtagjs":
            echo "if(typeof gtag != 'undefined') {" .
                "gtag('event', '" . $eventAction . "', {" .
                "'event_category': '" . $event . "'," .
                "'event_label': '" . $eventLabel . "'," .
                "'value': '" . $eventValue . "'" .
                "});" .
                "}";
            break;
    }
}
