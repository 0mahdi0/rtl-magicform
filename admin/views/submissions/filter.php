<ul class="mf-forms-left-nav">
    <li>
        <a class="active" href="javascript:void(0)">
            <?php esc_html_e("Filter Submissions", "magicform") ?>
        </a>
    </li>
</ul>
<form action="" method="get">
    <?php
    foreach ($_GET as $key => $value) {
        if ($key != "form_id" && $key != "read_status"  && $key != "q") {
            echo ("<input type='hidden' name='" . esc_attr($key) . "' value='" . esc_attr($value) . "'/>");
        }
    }
    ?>
    <div class="form-group">
        <label><?php esc_html_e("Form", "magicform") ?></label>
        <?php $selectedForm = isset($_REQUEST['form_id']) ? intval($_REQUEST['form_id']) : null; ?>
        <select id="forms_list" name="form_id" class="mf-admin-form-control">
            <option value="0"><?php esc_html_e("All Forms", "magicform") ?></option>
            <?php foreach ($formList as $form_id => $form_name) : ?>
                <option <?php echo ($form_id == $selectedForm) ? "selected" : "" ?> value="<?php echo esc_attr($form_id); ?>">
                    <?php echo esc_html($form_name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label><?php esc_html_e("Status", "magicform") ?></label>
        <select id="forms_list" name="read_status" class="mf-admin-form-control">
            <option value=""><?php esc_html_e("All", "magicform") ?></option>
            <option value="1" <?php echo isset($_GET["read_status"]) && $_GET["read_status"] === "1" ? "selected" : "" ?>><?php esc_html_e("Read", "magicform") ?></option>
            <option value="0" <?php echo isset($_GET["read_status"]) && $_GET["read_status"] === "0" ? "selected" : "" ?>><?php esc_html_e("Unread", "magicform") ?></option>
        </select>
    </div>
    <div class="form-group">
        <label><?php esc_html_e("Search", "magicform") ?></label>
        <input type="text" name="q" class="mf-admin-form-control" value="<?php echo isset($_GET["q"]) ? esc_attr($_GET["q"]) : "" ?>" placeholder="<?php esc_attr_e("Query", "magicform"); ?>">
    </div>
    <button class="mf-admin-btn mf-admin-btn-ghostblue">
        <i class="fas fa-search"></i>
        <?php esc_html_e("Filter", "magicform") ?>
    </button>
</form>