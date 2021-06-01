<?php

$postarr = array(
    "post_author" => $action->payload->postAuthor,
    "post_date" => date("Y-m-d H:i:s"),
    "post_title" => magicform_get_field_value($action->payload->postTitle, $formData),
    "post_content" => magicform_get_field_value($action->payload->postBody, $formData),
    "post_category" => array($action->payload->postCategory),
    "post_status" => $action->payload->postStatus,
    "post_type" => $action->payload->postType,
    "comment_status" => $action->payload->commentStatus,
    "tags_input" => str_replace(" ", ",", magicform_get_field_value($action->payload->tags, $formData))
);
wp_insert_post($postarr);