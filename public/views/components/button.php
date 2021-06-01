<?php
$payload = $item->payload;
$id = $item->id;
$href = $payload->buttonHref;
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id) ?>'>
    <div class="mf-button-<?php echo esc_attr($payload->buttonPosition) ?>">
        <a
        <?php if ($payload->eventType == "onclick") {?> 
            <?php if(!preg_match("/\"/i", $payload->buttonOnclick)):?>
                onclick="<?php echo esc_attr($payload->buttonOnclick)?>" 
            <?php endif;?>
        <?php } else if($payload->eventType == "href"){?>
            <?php if(preg_match('/https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&\/\/=]*)/', $payload->buttonHref)): ?>
                href="<?php echo esc_attr($payload->buttonHref)?>"<?=$payload->forwardType == "newTab"?"target='_blank'":"" ?>
            <?php endif;?>
        <?php } ?>
        id=<?php echo esc_attr($id); ?> class="mf-btn         
        <?php echo esc_attr($buttonSizes[$payload->buttonSize]); ?> 
        <?php echo ($payload->buttonWidth!="default") ? "mf-btn-width-".esc_attr($payload->buttonWidth) : "" ?>
 mf-btn-<?php echo esc_attr($payload->buttonType) ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>">
        <?php if($payload->buttonIcon):?> <i class="fas fa-<?php echo esc_attr($payload->buttonIcon)?>"></i> <?php endif;?>
        <?php echo esc_html($payload->buttonName) ?>
        </a>
    </div>
</div>