<div class="mf-forms-right-header">
    <h1><?php esc_html_e("My Forms", "magicform"); ?></h1>
    <div class="mf-forms-search">
        <div class="has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" name="q" value="<?php echo isset($_GET["q"]) ? esc_attr($_GET["q"]) : "" ?>" class="mf-admin-form-control" placeholder="<?php esc_attr_e("Search Forms", "magicform") ?>" />
        </div>
    </div>
</div>
<div class="mf-forms-right-inner">
    <?php if ($status===null || $status === 1) : ?>
        <div class="mf-card mf-card-add-form" data-toggle="modal" data-target="#mf-createform-modal">
            <div class="mf-card-body p-20">
                <i class="fas fa-plus-circle"></i>
                <h6><?php esc_html_e("Create Form", "magicform") ?></h6>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($allForms) == 0) : ?>
        <?php esc_html_e("There is no form", "magicform"); ?>
    <?php endif; ?>
    <?php foreach ($allForms as $form) :
        $formId = intval($form["id"]);
    ?>
        <div class="mf-card" data-link data-id="<?php echo esc_attr($formId) ?>" data-name="<?php echo esc_attr($form["form_name"]) ?>">
            <div class="mf-card-body p-20">
                <div class="mf-card-actions">
                    <div class="dropdown">
                        <a class="mf-card-action" href="javascript:void(0)" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?php echo esc_url("?page=magicform_forms&subpage=create&id=" . $formId); ?>">
                                <i class="fa fa-pen-fancy"></i>
                                <?php esc_html_e("Edit Form", "magicform"); ?>
                            </a>
                            <a class="dropdown-item" href="<?php echo esc_url("?page=magicform_submissions&form_id=" . $formId );?>">
                                <i class="fa fa-tasks"></i>
                                <?php esc_html_e("View Submissions", "magicform"); ?>
                            </a>
                            <a class="dropdown-item mf-dropdown-shortcode-btn">
                                <i class="fa fa-code"></i>
                                <?php esc_html_e("Copy ShortCode", "magicform"); ?>
                            </a>
                            <a class="dropdown-item mf-dropdown-clone-btn">
                                <i class="far fa-copy"></i>
                                <?php esc_html_e("Clone Form", "magicform"); ?>
                            </a>
                            <?php if($status == 2):?>
                                <a class="dropdown-item mf-remove-form-btn" style="color:red;" id="magicform_delete_form" >
                                    <i class="fas fa-trash"></i>
                                    <?php esc_html_e("Delete Form", "magicform"); ?>
                                </a>
                            <?php endif;?>
                            <?php if ($status != 2) : ?>
                                <a class="dropdown-item mf-remove-form-btn" id="magicform_archive_form" >
                                    <i class="far fa-file-archive"></i>
                                    <?php esc_html_e("Archive Form", "magicform"); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <h6><?php echo esc_html($form['form_name']) ?></h6>
                <ul class="mf-add-form-stats">
                    <?php $total = $form['read'] + $form['unread']; ?>
                    <li>
                        <?php echo intval($total) ?>
                        <?php esc_html_e("Submissions", "magicform"); ?>
                    </li>
                    <li>
                        <?php echo intval($form['views']) ?>
                        <?php esc_html_e("Views", "magicform"); ?>
                    </li>
                    <li>
                        <?php $percent = $form['views'] == 0 ? 0 : intval($total) * 100 / $form['views'];
                        echo intval($total) == 0 ? "%0" : ("%" . number_format((float) $percent, 2, '.', '')) ?>
                        <?php esc_html_e("Conversion", "magicform"); ?>
                    </li>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
</div>