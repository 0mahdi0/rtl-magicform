<?php

/**
 * @package Magic-Form
 */

class MagicForm_ArrayHelper
{
    public $allElements;
    public function register()
    {
        $this->allElements = array();
    }

    public function getElements($type, $data)
    {
        if ($type == "page") {
            foreach ($data as $page) {
                foreach ($page->elements as $item) {
                    $this->allElements[$item->id] = $item;
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->getElements($item->type, $item);
                    }
                }
            }
        }
        if ($type == "group") {
            foreach ($data->payload->elements as $item) {
                $this->allElements[$item->id] = $item;
                if ($item->type == "group" || $item->type == "grid") {
                    $this->getElements($item->type, $item);
                }
            }
        }
        if ($type == "grid") {
            foreach ($data->payload->columns as $column) {
                foreach ($column->elements as $item) {
                    $this->allElements[$item->id] = $item;
                    if ($item->type == "group" || $item->type == "grid") {
                        $this->getElements($item->type, $item);
                    }
                }
            }
        }
    }

    /**
     * Conditional Logic rules
     * Set rules field id 
    */

    function rules_set_id($rules, $insert_id) {
		foreach($rules as &$rule)
		{
			foreach($rule->actions as &$action){
				$newId = "_".$insert_id."_";
				$action->field = preg_replace('/\_(.*?)\_/',$newId, $action->field);
			}

			foreach($rule->conditions as &$cond){
				$newId = "_".$insert_id."_";
				$cond ->field = preg_replace('/\_(.*?)\_/',$newId, $cond->field);
			}
		}
		return $rules;
	}

    /**
     * Helper Template import set id
     */
    function form_set_id($type, $content, $id)
    {
        if ($type == "page") {
            foreach ($content as $page)
                foreach ($page->elements as $element) {
                    $newId = "_".$id."_";
                    $element->id = preg_replace('/\_(.*?)\_/',$newId,$element->id);
                    if ($element->type == "grid" || $element->type == "group") {
                        $this->form_set_id($element->type, $element, $id);
                    }
                }
        }

        if ($type == "group") {
            foreach ($content->payload->elements as $element) {
                $newId = "_".$id."_";
                $element->id = preg_replace('/\_(.*?)\_/',$newId, $element->id);
                if ($element->type == "grid" || $element->type == "group") {
                    $this->form_set_id($element->type, $element, $id);
                } 
            }
        }

        if ($type == "grid") {
            foreach ($content->payload->columns as $column) {
                foreach ($column->elements as $element) {
                    $newId = "_".$id."_";
                    $element->id = preg_replace('/\_(.*?)\_/',$newId, $element->id);
                    if ($element->type == "grid" || $element->type == "group") {
                        $this->form_set_id($element->type, $element, $id);
                    } 
                }
            }
        }
        return $content;
    }
}

