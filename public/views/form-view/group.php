<div data-type='group' class='mf-form-group-<?php echo esc_attr($el->id) ?>' id='<?php echo esc_attr($el->id) ?>'>
  <?php foreach ($el->payload->elements as $element) :
    if ($element->type == "grid" || $element->type == "group") {
      echo ($this->viewForm($element->type, $element,  $pageIndex));
    } else {
      echo ($this->buildElement($element, $pageIndex));
    }
  endforeach; ?>
</div>