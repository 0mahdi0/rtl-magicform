<div data-type='grid' class='mf-row mf-form-group-<?php echo esc_attr($el->id) ?>' id='<?php echo esc_attr($el->id) ?>'>
    <?php foreach ($el->payload->columns as $column) : ?>
        <div class='mf-col'>
            <?php foreach ($column->elements as $element) :
                if ($element->type == "grid" || $element->type == "group") {
                    echo ($this->viewForm($element->type, $element, $pageIndex));
                } else {
                    echo ($this->buildElement($element, $pageIndex));
                } ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>