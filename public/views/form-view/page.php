<?php
$pageCount = count($el);
$isMultiPage = $pageCount > 2;
$translate = $formSettings->translate;
$asd = "asd";
foreach ($el as $pageIndex => $page) : ?>
    <?php if ($page->type == "page") : ?>
        <div class='mf-page mf-page-<?php echo intval($pageIndex) ?>' data-index='<?php echo intval($pageIndex) ?>' <?php echo ($pageIndex > 0) ? "style='display:none'" : "" ?>>
            <?php if ($page->settings->titleVisible) : ?>
                <h2 class="mf-page-title"><?php echo esc_html($page->name); ?></h2>
                <p class="mf-page-description"><?php echo esc_html($page->settings->subTitle); ?></p>
            <?php endif; ?>
            <?php foreach ($page->elements as $element) :
                if ($element->type == "grid" || $element->type == "group") {
                    echo ($this->viewForm($element->type, $element, $pageIndex));
                } else {
                    echo ($this->buildElement($element, $pageIndex));
                }
            endforeach; ?>
            <?php if ($isMultiPage) : ?>
                <div class="mf-buttons mf-button-placement-<?php echo esc_attr($formSettings->multiStep->buttonPlacement) ?>">
                    <?php if ($pageIndex != 0 && ($pageIndex + 2) != $pageCount) : ?>
                        <button type="button" class="mf-back-btn">
                            <?php if ($formSettings->multiStep->backButtonIcon) : ?>
                                <i class="fas fa-<?php echo esc_attr($formSettings->multiStep->backButtonIcon) ?>"></i>
                            <?php endif; ?>
                            <?php echo esc_html($translate->previousButton); ?></button>
                    <?php endif; ?>
                    <?php if (($pageIndex + 2) != $pageCount) : ?>
                        <button type="button" class="mf-next-btn">
                            <?php echo esc_html($translate->nextButton); ?>
                            <?php if ($formSettings->multiStep->nextButtonIcon) : ?>
                                <i class="fas fa-<?php echo esc_attr($formSettings->multiStep->nextButtonIcon) ?>"></i>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php else :
        // Thank You Sayfası
    ?>
        <div class='mf-page mf-thankyou' data-index='<?php echo intval($pageIndex) ?>'>
            <?php
            // We support html in this place
            echo wp_kses($page->settings->message, array(
                'a' => array( 'href' => array(), 'title' => array(), 'target' => array()),
                'b' => array(),
                'img' => array("src"=>array()),
                'br' => array(),
                'em' => array(),
                'i' => array(),
                'strong' => array(),
                'p' => array("class" => array()),
                'h1' => array("class" => array()),
                'h2' => array("class" => array()),
                'h3' => array( "class" => array()),
                'h4' => array("class" => array()),
                'h5' => array("class" => array()),
                'h6' => array("class" => array()),
                "div" => array("class" => array()),
                "span" => array("style" => array("color" => array()))
            ), array("data"));
            ?>
        </div>

    <?php endif; ?>
<?php endforeach; ?>