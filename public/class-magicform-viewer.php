<?php

/**
 * @package Magic-Form
 */

class MagicForm_Viewer
{
    public $divCount;
    public $view = "";
    public $formId = null;
    public $pageElements = array();
    public $json = array();
    public $validations;
    public $allElements = array();
    private $formTable;
    private $submissionTable;
    public $column = "form_data";
    public $type = 1;
    public $submitType = "original";
    public $validationCheck = true;
    public $formLimit = true;
    public $fontFamily = null;
    private $wpdb;
    public $language;
    public $languages = array("cs","da", "de", "en", "es", "fi", "fr", "hu", "nl", "pl", "pt", "ro", "sk", "tr", "zh" );

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->formTable = $wpdb->prefix . "magicform_forms";
        $this->submissionTable = $wpdb->prefix . "magicform_submissions";
        $this->validations = array();
    }
    /**
     * Register Fonts
    */
    function register_fonts() {
        $fonts = array("Arial", "Century Gothic", "Times New Roman" , "Patrick Hand", "Roboto", "Roboto Condensed", "Open Sans", "Raleway","Nunito");
        foreach($fonts as $font){
            wp_register_style("magicform-font-".preg_replace('/\s+/', '-', strtolower($font)), magicform_setFontFamily($font));
        }
    }
    /**
     * Generate shortcode html
     */
    public function shortcode($props)
    {

        // Reset validations
        $this->validations = array();

        $this->formId = intval($props["id"]);
        if (empty($this->formId)) {
            return false;
        }
        $this->column = $this->type == 0 ? "preview_form_data" : "form_data";
        $this->validationCheck = ($this->type == 1 || ($_GET['validation']=="true" && isset($_GET['validation']))) === false ? false : true;
        
        $result = $this->wpdb->get_var($this->wpdb->prepare("SELECT " . $this->column . " FROM " . $this->formTable . " WHERE id=%d", $this->formId));
        $status = $this->wpdb->get_var($this->wpdb->prepare("SELECT status FROM " . $this->formTable . " WHERE id=%d", $this->formId));
        // Form deleted or wrong formId
        if (empty($result)) {
            return false;
        }

         // Enqueue Registered Css files
        $this->enqueu_styles();

         // Enqueue Registered js files
        $this->enqueue_scripts();
     

        $json = json_decode($result);
        $this->json = $json;
        $formSettings = $json->settings;

        // Settings active controle 
        if ($this->type === 1 &&  $status != 1) {
            return false;
        }

        // set Font family
        $this->fontFamily = $formSettings->fontFamily;
        $this->language = $this->find_language( $formSettings->selectLanguage );

        // Dynamic assets
        wp_enqueue_style("magicform-font-".preg_replace('/\s+/', '-', strtolower($this->fontFamily)));
        add_action('wp_footer', array($this, 'enqueueDynamicAssets'));

        // Update View Count
        $this->updateViewCount();

        // Check Form Limit
        $this->formLimit = $this->checkSubmissionLimit($formSettings);

        // Load Dependencies
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/inputDescription.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/inputIcon.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/inputLabel.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/progressBar.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/gdpr.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/customCss.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/countries.php");
        require_once(MAGICFORM_PATH . "/public/views/components/sub-components/loadingIcon.php");

        
       
        return $this->viewForm("form", $json);
    }

    /**
     * View form
     */
    function viewForm($type, $el, $pageIndex = 0)
    {
        $submitType = $this->submitType;
        $formSettings = $this->json->settings;
        $formActions = $this->json->actions;
        ob_start();
        include(MAGICFORM_PATH . "/public/views/form-view/" . $type . ".php");
        $data =  ob_get_contents();
        ob_end_clean();
        return $data;
    }

    /**
     * Build element html
     */
    public function buildElement($item, $pageIndex)
    {
        $formId = $this->formId;
        $formSettings = $this->json->settings;
        $pageSettings = $this->json->pages[$pageIndex]->settings;
        $pageCount = count($this->json->pages);
        $isMultiPage = $pageCount > 2;
        $buttonTypes = $this->buttonTypes();
        $fieldSizes = $this->fieldSizes();
        $fieldWidths = $this->fieldWidths();
        $buttonSizes = $this->buttonSizes();
        $themeName = $formSettings->theme->name;
        $actions = $this->json->actions;
        $this->getInheritedValues(array("labelPosition", "labelDisplay", "labelWidth", "fieldSize", "fieldWidth", "buttonWidth", "buttonSize"), $item, $pageSettings, $formSettings);
        ob_start();

        if (isset($item->payload->labelPosition) && $item->payload->labelPosition == "inline") {
            echo "<style>" .
                ".mf-form-group-" . $item->id . " .mf-label-inline { width: " . $item->payload->labelWidth . "px  }" .
                ".mf-form-group-" . $item->id . " .mf-input-container { padding-left: " . $item->payload->labelWidth . "px  }" .
                "</style>";
        }

        if (isset($item->payload->defaultValue) && $item->payload->defaultValue)
            $item->payload->defaultValue = $this->defaultValueParser($item->payload->defaultValue);

        include(MAGICFORM_PATH . "/public/views/components/" . $item->type . ".php");
        $data = ob_get_contents();
        ob_end_clean();

        $this->pageElements[$pageIndex][$item->id] = $item;
        $this->allElements[$item->id] = $item;
        if (isset($item->payload->confirmInput) && $item->payload->confirmInput) {
            $confirmError = array("type" => "match", "payload" => (object) array("errorMessage" => $formSettings->translate->confirmErr, "id" => $item->id));
            $this->validations[$item->id][] = (object) $confirmError;
        }

        // Add to validation list if  has a required field, for front-end validation
        if (isset($item->payload->required) && $item->payload->required) {
            $requiredObject = array("type" => "required", "payload" => (object) array("errorMessage" => $formSettings->translate->requiredErr));
            $this->validations[$item->id][] = (object) $requiredObject;
        }

        // Add to validation list if  has a required field, for front-end validation
        if (isset($item->payload->validations) && is_array($item->payload->validations) && count($item->payload->validations) > 0)
            foreach ($item->payload->validations as $v) {
                $this->validations[$item->id][] = $v;
            }
        return $data;
    }

    public function enqueueDynamicAssets()
    {
        // Datepicker language
        wp_enqueue_script("magicform-datepicker-language",MAGICFORM_URL . "assets/js/datepicker.". $this->language  .".js");
    }
  
    function enqueu_styles() {
        wp_enqueue_style("font-awesome-5");
        wp_enqueue_style("jquery-datepicker");
        wp_enqueue_style("magicform-general");
        wp_enqueue_style("magicform-theme");       
    }

    function enqueue_scripts() {
        wp_enqueue_script("signature");
        wp_enqueue_script("jquery-datepicker");
        wp_enqueue_script("magicform-main");
    }

    /**
     * Check submission limit
     */
    private function checkSubmissionLimit($formSettings)
    {
        $subCount = $this->wpdb->get_var($this->wpdb->prepare("SELECT COUNT(id) FROM " . $this->submissionTable . " where form_id=%d", $this->formId));
        if ($formSettings->subLimit != 0) {
            if ($subCount >= $formSettings->subLimit)
                return false;
        } else if ($formSettings->endSubDate != "") {
            if (strtotime(date("Y-m-d H:i:s")) >= strtotime($formSettings->endSubDate))
                return false;
        }
        return true;
    }

    
    /**
     * Update view count
     */
    private function updateViewCount()
    {
        if ($this->type == 1) {
            $views = $this->wpdb->get_var($this->wpdb->prepare("SELECT views FROM " . $this->formTable . " WHERE id =%d", $this->formId));
            $this->wpdb->update($this->formTable, array(
                'views' => $views + 1
            ), array("id" => $this->formId));
        }
    }

    /**
     * Default value parse for get or post parameters
     */
    public function defaultValueParser($defaultValue)
    {
        return preg_replace_callback('/\{([^{}]+)\}/', function ($match) {
            $variable = preg_replace('/\s+/', '', $match[1]);
            return $_REQUEST[$variable];
        }, $defaultValue);
    }

    /**
     * Change if inherit values like button Style, field Width etc.
     */
    public function getInheritedValues($attrs, &$obj, $pageS, $formS)
    {
        $newObj = (array) $obj->payload;
        $pageS = (array) $pageS;
        $formS = (array) $formS;
        foreach ($attrs as $attr) {
            if (isset($newObj[$attr])) {
                $val = $newObj[$attr] == "inherit" ? ($pageS[$attr] == "inherit" ? $formS[$attr] : $pageS[$attr]) : $newObj[$attr];
                $newObj[$attr] = $val;
            }
        }
        $obj->payload = (object) $newObj;
    }

    /**
     * Hex to Rgba 
     */
    function hex2rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided 
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    /**
     * Button Types
     */
    function buttonTypes()
    {
        $buttonTypes = array(
            "default" => "",
            "small" => "mf-btn-sm",
            "large" => "mf-btn-lg"
        );
        return $buttonTypes;
    }

    /**
     * Field Sizes
     */
    function fieldSizes()
    {
        $fieldSizes = array(
            "default" => "",
            "small" => "mf-form-control-sm",
            "large" => "mf-form-control-lg"
        );
        return $fieldSizes;
    }

    /**
     * Button Sizes
     */
    function buttonSizes()
    {
        $buttonSizes = array(
            "default" => "",
            "small" => "mf-btn-sm",
            "large" => "mf-btn-lg"
        );
        return $buttonSizes;
    }

    /**
     * Field Widths
     */
    function fieldWidths()
    {
        $fieldWidths = array(
            "inherit" => "",
            "tiny" => "mf-form-width-xs",
            "small" => "mf-form-width-sm",
            "medium" => "mf-form-width-md",
            "large" => "mf-form-width-lg",
            "full" => "mf-form-width-full"
        );
        return $fieldWidths;
    }

    /**
     * Datepicker find languages
     */
    function find_language($lang){
        $lang = strtolower($lang);
        foreach($this->languages as $language)
            if($language == $lang)
                return $lang;

        return "en";
    }
}
