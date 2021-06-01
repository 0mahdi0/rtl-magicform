<?php
$templatesObject = $this->get_templates();
 ?>
<div class="mf-mainContainer mf-mainContainerAdmin">
    <div class="mf-contentContainer">
        <div class="mf-header">
            <div class="mf-header-left">
                <h1>
                    <a class="mf-logo" href="<?php echo esc_url("?page=magicform_admin"); ?>">
                        <img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/logo_light.svg"); ?>" />
                    </a>
                </h1>
            </div>
            <div class='mf-header-center'></div>
            <?php require_once(MAGICFORM_PATH . "/admin/views/components/header-right.php"); ?>
        </div>

        <div class="mf-forms">
            <div class="mf-forms-left">
                <?php require_once("left-nav.php"); ?>
            </div>
            <div class="mf-forms-right">
                <?php require_once("template-list.php"); ?>
            </div>
        </div>
    </div>
</div>
<?php require_once(MAGICFORM_PATH . "/admin/views/forms/modals.php");  ?>