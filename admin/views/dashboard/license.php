<div class="mf-card">
    <div class="mf-card-body mf-p-20">
        <h6><?php esc_html_e("License", "magicform") ?></h6>
        <?php if (get_option("magicform_purchase_code") != "") : $license = json_decode(get_option("magicform_purchase_code")) ?>
            <p class="mf-license-active">
                <i class="fas fa-check"></i>
                <?php esc_html_e("You have license for this plugin.", "magicform") ?></p>
            <table class="mf-licenseTable">
                <tr>
                    <td width="110"><b><?php esc_html_e("License Code", "magicform") ?>:</b></td>
                    <td><?php echo esc_html($license->code) ?></td>
                </tr>
                <tr>
                    <td><b><?php esc_html_e("Domain", "magicform") ?>:</b></td>
                    <td><?php echo esc_html($license->domain) ?></td>
                </tr>
            </table>
            </br>
            <a class="mf-admin-btn mf-admin-btn-red mf-deactivate-btn">
                <i class="fas fa-trash"></i>
                <?php esc_html_e("Deactivate Licence", "magicform") ?>
            </a>
        <?php endif; ?>
        <div class="mf-activate-result"></div>
        <?php if (get_option("magicform_purchase_code") == "") : ?>
            <p class="mf-license-notactive">
                <i class="fas fa-exclamation"></i>
                <?php esc_html_e("You do not have license for this plugin.", "magicform") ?></p>
            <div class="mf-result"></div>
            <p><?php esc_html_e("If you have a license code please activate plugin", "magicform") ?></p>
            <div class="form-group">
                <input type="text" class="mf-admin-form-control mf-form-control-sm" id="magicform_purchase_code" placeholder="<?php esc_attr_e("License Code", "magicform") ?>" />
            </div>
            <p>
                <a class="mf-admin-btn mf-admin-btn-blue mf-activate-btn">
                    <i class="fas fa-thumbs-up"></i>
                    <?php esc_html_e("Activate Plugin", "magicform") ?>
                </a>
            </p>
            <?php require_once(MAGICFORM_PATH."/admin/views/components/upgrade.php"); ?>
        <?php endif; ?>
        <p style="padding-top:10px;"> <?php echo esc_html_e("MagicForm Version: ","magicform") . MAGICFORM_VERSION;  ?> </p>
    </div>
</div>