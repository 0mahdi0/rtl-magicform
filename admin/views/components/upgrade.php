<?php if (get_option("magicform_purchase_code") == "") : ?>
    <div class="mf-upgrade">
        <p><?php esc_html_e("You have to purchase license to use pro features", "magicform") ?>:</p>
        <ul>
            <li><?php esc_html_e("Strong support that solves your problem.", "magicform"); ?></li>
            <li><?php esc_html_e("Get updates lifetime", "magicform"); ?></li>
            <li><?php esc_html_e("Actions: Form to Post, Generate PDF, Auto Responder and smart integrations", "magicform"); ?></li>
            <li><?php esc_html_e("Unlimited forms", "magicform"); ?></li>
            <li><?php esc_html_e("All themes and customize form design", "magicform"); ?></li>
            <li><?php esc_html_e("Pre-defined Templates", "magicform"); ?></li>
        </ul>
        <p><?php esc_html_e("We offer our Pro version includes our best features for only $25. Upgrade today and increase your customer interactions.", "magicform") ?></p>
        <a class="mf-admin-btn mf-admin-btn-ghostblue" href="https://1.envato.market/M7aVM" target="_blank">
            <i class="fas fa-shopping-cart"></i>
            <?php esc_html_e("Purchase Plugin (Only $25)", "magicform") ?>
        </a>
    </div>
<?php endif; ?>