<div class="mf-mainContainer mf-mainContainerAdmin">
  <div class="mf-contentContainer">
    <div class="mf-header">
      <div class="mf-header-left">
        <h1>
          <a class="mf-logo" href="<?php echo esc_url("?page=magicform_admin") ?>">
            <img src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/logo_light.svg") ?>" />
          </a>
        </h1>
      </div>
      <div class='mf-header-center'></div>
      <?php require_once(MAGICFORM_PATH . "/admin/views/components/header-right.php"); ?>
    </div>
    <div class="container mf-container-with-padding">
      <div class="row">
        <div class="col-sm-8">
          <?php require_once("stats.php"); ?>
          <?php require_once("latest-submissions.php"); ?>
        </div>
        <div class="col-sm-4">
          <?php
              $extended_control = apply_filters("magicform_extended_check_license","notextended",10,3);
              $demo_control = apply_filters("magicform_demo_check_license","notdemo",10,3);
              if($extended_control !== "extended" && $demo_control !== "demo")
                require_once("license.php"); 
          ?>
          <div class="mf-card">
            <div class="mf-card-body p-20">
              <h6><?php esc_html_e("Documentation", "magicform"); ?></h6>
              <p><?php esc_html_e("Read documentation to learn how to use Magic Form.", "magicform"); ?></p>
              <a class="mf-admin-btn mf-admin-btn-ghostblue" href="<?php echo esc_url("https://docs.magicform3.com/")?>" target="_blank">
                <i class="fas fa-book"></i>
                <?php esc_html_e("Read Documentation", "magicform"); ?>
              </a>
            </div>
          </div>
          <div class="mf-card">
            <div class="mf-card-body p-20">
              <h6><?php esc_html_e("Support & Help", "magicform") ?></h6>
              <p><?php
                  esc_html_e("Have a question? We've got answers!","magicform");
                  printf(wp_kses(
                    __("You can ask every question by support system %s", "magicform"),
                    array('a' => array('href' => array(),'title' => array()))
                  ),"<a target='_blank' href='https://magicform3.com/support'>magicform3.com/support</a>"); ?>
              </p>
              <a class="mf-admin-btn mf-admin-btn-ghostblue" href="<?php echo esc_url("https://magicform3.com/support");?>" target="_blank">
                <i class="fas fa-question-circle"></i> 
                <?php esc_html_e("Get Support", "magicform"); ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>