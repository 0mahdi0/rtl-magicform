<?php
if (!function_exists("magicform_getProgressBar")) {
    function magicform_getProgressBar($settings, $position)
    {
        if ($settings->show) :
            if ($position == $settings->position) : ?>
                <div class="mf-progress" style="height:<?php echo intval($settings->height) ?>px; background-color:<?php echo magicform_convertRgba($settings->bgColor) ?>">
                    <div class="mf-progress-bar <?php echo ($settings->striped) ? "mf-progress-bar-striped" : "" ?> <?php echo ($settings->animated) ? "mf-progress-bar-animated" : "" ?>" <?php echo ($settings->showPercent) ? "percent" : "" ?> role="progressbar" style="background-color:<?php echo magicform_convertRgba($settings->color); ?>" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
        <?php endif;
        endif;
    }
}
