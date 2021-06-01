<div class="mf-mainContainer mf-mainContainerAdmin">
    <div class="mf-contentContainer">
        <div class="mf-feedback-form-container">
            <div class="card">
                <h5 class="card-header">
                    <img class="mf-logo" src="<?php echo esc_url(MAGICFORM_URL . "/assets/images/logo_light.svg"); ?>" /></h5>
                <div class="card-body">
                    <h5 class="card-title"><?php esc_html_e("Feedback", "magicform"); ?></h5>
                    <p>
                        <?php esc_html_e("Hi, we are thrilled you chose to purchase the magicform from us. ", "magicform");
                        esc_html_e("We are working hard to build a higher quality product for our customers. We would love to learn more about your opinion.", "magicform");
                        esc_html_e("Please give us your feedback. Thank you.", "magicform"); ?></p>
                    <form name='mf-feedback-form'>
                        <div class="form-group">
                            <label for="mf-email" class="col-form-label"><?php esc_html_e("E-Mail (Optional)", "magicform") ?></label>
                            <input type="email" class="mf-admin-form-control" name="email" placeholder="<?php esc_attr_e("Email", "magicform") ?>">
                        </div>
                        <div class="form-group">
                            <label class="" col-form-label"><?php esc_html_e("Message", "magicform") ?></label>
                            <textarea name="message" class="mf-admin-form-control" placeholder="<?php esc_attr_e("Message", "magicform") ?>"></textarea>
                        </div>
                        <div class="form-group">
                            <label class=" col-form-label"><?php esc_html_e("Rating", "magicform"); ?></label>
                            <div class="mf-rate-container">
                                <div class="mf-rate">
                                    <input type="radio" id="5" name="rating" value="5" />
                                    <label for="5" title="5"></label>
                                    <input type="radio" id="4" name="rating" value="4" />
                                    <label for="4" title="4"></label>
                                    <input type="radio" id="3" name="rating" value="3" />
                                    <label for="3" title="3"></label>
                                    <input type="radio" id="2" name="rating" value="2" />
                                    <label for="2" title="2"></label>
                                    <input type="radio" id="1" name="rating" value="1" />
                                    <label for="1" title="1"></label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="mf-result"></div>
                    <button type="submit" class="mf-admin-btn mf-admin-btn-green mf-send-feedback">
                        <i class="far fa-check-circle"></i> <?php esc_html_e("Submit", "magicform") ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>