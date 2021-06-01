<?php

/**
 *  Create Form Modal
 */
?>
<div class="modal fade in" id="mf-createform-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e("Create Form", "magicform"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e("Close", "magicform") ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label><?php esc_html_e("Form Name", "magicform"); ?></label>
                        <input type="text" maxlength="50" class="mf-admin-form-control" name="form_name" placeholder="<?php esc_attr_e("e.g Contact Form", "magicform") ?>">
                        <small class="form-text text-muted"><?php esc_attr_e("Max 50 chars", "magicform"); ?></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mf-admin-btn mf-admin-btn-blue mf-create-form-btn">
                    <i class="fas fa-plus-circle"></i> 
                    <?php esc_html_e("Create Form", "magicform"); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php
/**
 *  Archive Form Modal
 */
?>
<div class="modal fade in" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header danger">
                <h5 class="modal-title"><?php esc_html_e("Archive Form", "magicform"); ?></h5>
            </div>
            <div class="modal-body">
            <?php printf(esc_html__("Are you sure to archive %s", "magicform"), "<b class='formName'></b>"); ?>
                <div class="mf-delete-message"><?php echo esc_html_e("If you delete the form, the submissions of the form will also be deleted.","magicform")?></div>
                <input name="formId" type="hidden" />
                <input name="action" type="hidden" />
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" href="#" class="mf-admin-btn">
                    <?php esc_html_e("Cancel", "magicform"); ?>
                </a>
                <a id="archive" href="javascript:void(0)" class="mf-admin-btn mf-admin-btn-red mf-delete-form-btn">
                    <i class="fas fa-trash-alt"></i>
                    <?php esc_html_e("Archive", "magicform"); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
/**
 *  Copy Short Code Modal 
 */
?>
<div class="modal fade in" id="mf-shortcode-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e("Short Code", "magicform"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e("Close", "magicform") ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <label><?php esc_html_e("Insert this shortcode to display inline (Recommended)", "magicform"); ?></label>
                    <div class="form-row align-items-center">
                        <div class="col-8 my-1">
                            <input type="text" class="mf-admin-form-control" id="mf-shortcode-input1" readonly>
                        </div>
                        <div class="col-4 my-1">
                            <button class="mf-admin-btn mf-admin-btn-blue" id="mf-copy-shortcode1" data-href="mf-shortcode-input1" type="button">
                                <i class="fas fa-copy"></i>
                                <?php esc_html_e("Copy", "magicform"); ?>
                            </button>
                        </div>
                    </div>
                    <small class="mf-form-text mf-text-muted"><?php echo wp_kses(
                                __("Don't know how to use shortcodes? Learn <a target='_blank' href='https://www.wpbeginner.com/wp-tutorials/how-to-add-a-shortcode-in-wordpress/'>here</a>", "magicform"),
                                array('a' => array('href' => array(), 'title' => array()))
                            ); ?></small>
                    <label class="mf-mt-30"><?php esc_html_e("Php usage to display form inline (Advanced Usage)", "magicform"); ?></label>
                    <div class="form-row align-items-center">
                        <div class="col-8 my-1">
                            <input type="text" class="mf-admin-form-control" id="mf-shortcode-input2" readonly>
                        </div>
                        <div class="col-4 my-1">
                            <button class="mf-admin-btn mf-admin-btn-blue" id="mf-copy-shortcode2" data-href="mf-shortcode-input2"  type="button">
                                <i class="fas fa-copy"></i>
                                <?php esc_html_e("Copy", "magicform"); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

/**
 *  Import Form Modal
 */
?>
<div class="modal fade in" id="mf-importform-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e("Import Form", "magicform"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e("Close", "magicform") ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label><?php esc_html_e("Form Name", "magicform"); ?></label>
                        <input type="text" maxlength="50" class="mf-admin-form-control" id="import_form_name" name="form_name" placeholder="<?php esc_attr_e("e.g Contact Form", "magicform") ?>">
                        <small class="form-text text-muted"><?php esc_attr_e("Max 50 chars", "magicform"); ?></small>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile"><?php esc_html_e("Choose File", "magicform"); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mf-admin-btn mf-admin-btn-blue mf-import-form-btn">
                    <i class="fas fa-plus-circle"></i> 
                    <?php esc_html_e("Import Form", "magicform"); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php

/**
 *  Create Form Modal
 */
?>
<div class="modal fade in" id="mf-purchase-warning-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php esc_html_e("Form Creation Limited", "magicform"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e("Close", "magicform") ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label><?php esc_html_e("Your form creation limit is 2. If you want to limitless forms and more features, you have to purchase.", "magicform"); ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="mf-admin-btn mf-admin-btn-ghostblue" href="https://1.envato.market/M7aVM" target="_blank">
                    <i class="fas fa-shopping-cart"></i>
                    <?php esc_html_e("Purchase Plugin (Only $25)", "magicform") ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
/**
 *  Delete Form Modal
 */
?>
<div class="modal fade in" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header danger">
                <h5 class="modal-title"><?php esc_html_e("Delete Form", "magicform"); ?></h5>
            </div>
            <div class="modal-body">
                <?php printf(esc_html__("Are you sure to delete %s If you delete the form, the submissions of the form will also be deleted.", "magicform"), "<b class='formName'></b>"); ?>
                <input name="formId" type="hidden" />
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" href="#" class="mf-admin-btn">
                    <?php esc_html_e("Cancel", "magicform"); ?>
                </a>
                <a id="mf-delete-form-btn" href="javascript:void(0)" class="mf-admin-btn mf-admin-btn-red">
                    <i class="fas fa-trash-alt"></i>
                    <?php esc_html_e("Delete", "magicform"); ?>
                </a>
            </div>
        </div>
    </div>
</div>
