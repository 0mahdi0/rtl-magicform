<ul class="mf-forms-left-nav">
    <li>
        <a href="<?php echo esc_url("?page=magicform_templates"); ?>">
            <?php esc_html_e("Templates", "magicform"); ?>
        </a>
        <ul>
            <?php $category = isset($_GET["category"]) ? $_GET["category"] : null; ?>
            <?php foreach ($templatesObject->categories as $cat) : ?>
                <li>
                    <a class="<?php echo ($cat == $category) ? "active" : "" ?>" href="<?php echo esc_url("?page=magicform_templates&category=" . esc_attr($cat)); ?>">
                        <?php echo ucfirst(esc_html($cat)); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>