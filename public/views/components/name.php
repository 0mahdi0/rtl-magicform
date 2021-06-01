<?php
$payload = $item->payload;
$id = $item->id;
?>
<div class='mf-form-group-<?php echo esc_attr($id); ?>'>
    <?php magicform_getInputLabel($payload,strpos($themeName,"material") === 0) ?>
    <div class="mf-input-container">
        <?php magicform_getInputDescription($payload, "top") ?>
        <div class="mf-form-inline mf-form-inline-align-top">
            <?php if ($payload->title) : ?>
                <select <?php echo ($payload->disabled) ? "disabled" : "" ?>  type='text' class="mf-form-control mf-nameinput-title <?php echo esc_attr($fieldSizes[$payload->fieldSize]) ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" id="<?php echo esc_attr($id); ?>_title" name="<?php echo esc_attr($id); ?>_title">
                    <?php 
                    foreach($payload->options as $option)
                        echo "<option value='". strtolower($option->name) . "'>". $option->name  ."</option>";
                    ?>
                </select>
            <?php endif; ?>
            <div class="mf-form-group mf-nameinput-name">
                <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '' ?>
                <input  <?php echo ($payload->disabled) ? "disabled" : "" ?>  type="text" name="<?php echo esc_attr($id); ?>_name" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?=esc_attr($payload->namePlaceholder) ?>" id="<?php echo esc_attr($id); ?>" class="mf-form-control mf-nameinputs <?php echo esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
                <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">' . esc_html($payload->namePlaceholder) . '</span>' : ''; ?>
                <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                <div class="mf-error"></div>
            </div>
            <?php if ($payload->middle) : ?>
                <div class="mf-form-group mf-nameinput-middle">
                <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '' ?>
                    <input  <?php echo ($payload->disabled) ? "disabled" : "" ?>  type="text"  name="<?php echo esc_attr($id); ?>_middle" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?=esc_attr($payload->middlePlaceholder)?>" id="<?php echo esc_attr($id); ?>_middle" class="mf-form-control mf-nameinputs <?php echo  esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_html(implode(" ", $payload->cssClasses))?>" />
                    <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">'.esc_html($payload->middlePlaceholder).'</span>' : ''; ?>
                    <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                    <div class="mf-error"></div>
                </div>
            <?php endif; ?>
            <?php if ($payload->surname) : ?>
                <div class="mf-form-group mf-nameinput-surname">
                    <?php echo strpos($themeName,"material") === 0 ? '<div class="mf-material-container">' : '' ?>
                    <input  <?php echo ($payload->disabled) ? "disabled" : "" ?>  type="text" name="<?php echo esc_attr($id); ?>_surname" <?php echo ($payload->readOnly) ? "readonly" : "" ?> placeholder="<?=esc_attr($payload->surnamePlaceholder)?>" id="<?php echo esc_attr($id); ?>_surname" class="mf-form-control mf-nameinputs <?php echo  esc_attr($fieldSizes[$payload->fieldSize]); ?> <?php echo esc_html(implode(" ", $payload->cssClasses)) ?>" />
                    <?php echo strpos($themeName,"material") === 0 ? '<span class="mf-material-label">'.esc_html($payload->surnamePlaceholder).'</span>' : ''; ?>
                    <?php echo strpos($themeName,"material") === 0 ? '</div>' : '' ?>
                    <div class="mf-error"></div>
                </div>
            <?php endif; ?>
        </div>
        <?php magicform_getInputDescription($payload, "bottom") ?>
    </div>
</div>