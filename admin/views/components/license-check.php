<?php
if (get_option("magicform_purchase_code") == "") : ?>
    <div class="mf-p-20">
        <p class="mf-license-notactive">
            <i class="fas fa-exclamation"></i>
            <?php esc_html_e("You do not have license for this plugin.", "magicform"); ?>
        </p>
        <?php printf(wp_kses(
            __("Go to <a href='%s'>dashboard</a> and activate your plugin!", "magicform"),
            array('a' => array('href' => array(), 'title' => array()))
        ), "?page=magicform_admin");
        ?>
    </div>
<?php
    wp_die();
endif;
