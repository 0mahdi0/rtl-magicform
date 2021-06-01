<?php
if ($isMultiPage && $formSettings->multiStep->visible) : ?>
    <div class="mf-multistep-header mf-multistep-<?php echo esc_attr($formSettings->multiStep->theme); ?>">
        <ul class="mf-multistep-nav">
            <?php $i = 1;
            foreach ($el->pages as $page) : ?>
                <?php if ($page->type == "page") : ?>
                    <li class="mf-multistep-nav-item" data-index="<?php echo intval($i - 1) ?>">
                        <span class="mf-multistep-page-icon">
                            <?php if ($formSettings->multiStep->type == "number") echo intval($i); ?>
                            <?php if ($formSettings->multiStep->type == "icon") echo '<i class="fas fa-' . $page->step->icon . '"></i>'; ?>
                        </span>
                        <div class="mf-multistep-page-title"><?php echo esc_html($page->step->title) ?></div>
                        <div class="mf-multistep-page-subtitle"><?php echo esc_html($page->step->description) ?></div>
                    </li>
                <?php endif;
                $i++; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif;
