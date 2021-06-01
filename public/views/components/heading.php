<?php
$payload = $item->payload;
$id = $item->id;
echo '<'.$payload->element.' class="mf-position-'. esc_attr($payload->elementPosition).' '.esc_html(implode(" ", $payload->cssClasses)).'" id="' .esc_attr($id) .'" 
style="color:'.esc_attr(magicform_convertRgba($payload->color)).'; background-color:'. esc_attr(magicform_convertRgba($payload->backgroundColor)).';">
        '.esc_html($payload->content).'  </'.$payload->element.'>';
      
?>
    