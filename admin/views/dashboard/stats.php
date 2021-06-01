<?php
$totalForms = $this->total_forms();
$totalSub = $this->total_submissions();
$newSubmissions = $this->new_submissions();
$passiveFormCount = $this->passive_forms_count();
?>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <div class="mf-card mf-dashboard-stats-item">
            <div class="mf-card-body mf-p-20">
                <h6><?php esc_html_e("Total Forms", "magicform") ?></h6>
                <span class="mf-dashboard-value"><?php echo intval($totalForms) ?></span>
                <p>
                    <?php esc_html_e("Active", "magicform") ?>: <?php echo intval($totalForms - $passiveFormCount) ?> /
                    <?php esc_html_e("Inactive", "magicform") ?>: <?php echo intval($passiveFormCount); ?>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="mf-card mf-dashboard-stats-item">
            <div class="mf-card-body mf-p-20">
                <h6><?php esc_html_e("Total Submissions", "magicform") ?></h6>
                <span class="mf-dashboard-value"><?php echo intval($totalSub) ?></span>
                <p>
                    <a href="<?php echo esc_url("?page=magicform_submissions") ?>"><?php esc_html_e("See all submissions", "magicform") ?></a>
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="mf-card mf-dashboard-stats-item">
            <div class="mf-card-body mf-p-20">
                <h6><?php esc_html_e("New Submissions", "magicform") ?></h6>
                <span class="mf-dashboard-value"><?php echo intval($newSubmissions) ?></span>
                <p>
                    <a href="<?php echo esc_url("?page=magicform_submissions&read_status=0") ?>"><?php esc_html_e("Read Submissions", "magicform") ?></a>
                </p>
            </div>
        </div>
    </div>
</div>