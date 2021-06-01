<div class="mf-forms-right-header">
    <h1><?php esc_html_e("Templates", "magicform") ?></h1>
</div>
<div class="mf-forms-right-inner">
    <?php if (count($templatesObject->templates) == 0) : ?>
        <?php esc_html_e("There is no template", "magicform"); ?>
    <?php endif; ?>
    <?php foreach ($templatesObject->templates as $template) : ?>
        <div class="mf-card">
            <div class="mf-card-body p-20">
                <div class="mf-card-actions">
                </div>
                <h6><?php echo esc_html($template->name) ?></h6>
                <p class="text-muted"><?php echo esc_html($template->desription) ?></p>
                <div data-id="<?php echo esc_attr($template->id) ?>" class="mf-template" data-toggle="modal" data-target="#mf-createform-modal">
                    <a class="mf-admin-btn mf-admin-btn-ghostblue">
                        <i class="fa fa-upload"></i>
                        <?php esc_html_e("Import Template", "magicform"); ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>