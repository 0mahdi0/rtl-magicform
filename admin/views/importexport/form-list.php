<div class="mf-forms-right-inner">
    <?php if (count($allForms) == 0) : ?>
        <?php esc_html_e("There is no form", "magicform"); ?>
    <?php endif; ?>
    <?php foreach ($allForms as $form) : ?>
        <a href="<?php echo esc_url("?action=magicform_export&id=" . intval($form['id'])); ?>">
            <div class="mf-card mf-card-add-form">
                <div class="mf-card-body p-20">
                    <h6><?php echo esc_html($form['form_name']) ?></h6>
                    <i class="fas fa-download"></i>
                    <h6><?php esc_html_e("Export Form", "magicform") ?></h6>
                </div>
            </div>
        </a>
    <?php endforeach; ?>
</div>