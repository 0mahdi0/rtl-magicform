<?php
if (!function_exists("magicform_getInputDescription")) {
    function magicform_getInputDescription($payload, $position)
    {
        $description = trim(isset($payload->description)?$payload->description:"");
        if ($description != "" &&  $position == $payload->descriptionPosition) : ?>
            <small class="mf-form-text mf-form-text-<?php echo esc_attr($payload->descriptionPosition) ?> mf-text-muted"> <?php echo esc_attr($description) ?> </small>
        <?php endif; ?>
<?php
    }
}
