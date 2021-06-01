<ul class="mf-forms-left-nav">
    <li><a href="<?php echo esc_url("?page=magicform_forms")?>"><?php esc_html_e("My Forms", "magicform"); ?></a>
        <?php $status = isset($_GET["active"]) ? intval($_GET["active"]) : null ?>
        <ul>
            <li>
                <a class="<?php echo ($status===null || $status === 1) ? "active" : "" ?>" href="<?php printf(esc_url("?page=magicform_forms&active=%d"),1);?>">
                    <?php esc_html_e("Active Forms", "magicform"); ?>
                </a>
            </li>
            <li>
                <a class="<?php echo ($status === 0) ? "active" : "" ?>" href="<?php printf(esc_url("?page=magicform_forms&active=%d"),0);?>">
                    <?php esc_html_e("Inactive Forms", "magicform"); ?>
                </a>
            </li>
            <li>
                <a class="<?php echo ($status === 2) ? "active" : "" ?>" href="<?php printf(esc_url("?page=magicform_forms&active=%d"),2);?>">
                    <?php esc_html_e("Archived Forms", "magicform"); ?>
                </a>
            </li>
        </ul>
    </li>
</ul>