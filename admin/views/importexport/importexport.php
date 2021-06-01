<?php
$allForms = $this->get_forms();
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
                <?php if (isset($_GET["sub_page"]) && $_GET['sub_page'] == "export") : ?>
                    <?php require_once("form-list.php"); ?>
                <?php else : ?>
                    <div class="mf-forms-right-inner">
                        <div class="mf-card mf-card-add-form" data-toggle="modal" data-target="#mf-importform-modal">
                            <div class="mf-card-body p-20">
                                <i class="fas fa-upload"></i>
                                <h6><?php esc_html_e("Import Form", "magicform") ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once(MAGICFORM_PATH . "/admin/views/forms/modals.php"); ?>