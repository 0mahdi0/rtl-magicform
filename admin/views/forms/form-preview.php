<?php
/**
 * Blank Page
 * for preview
 */

?>
<!DOCTYPE html>
<html>	
<head>
    <link rel="stylesheet" href="<?php echo MAGICFORM_URL . 'assets/css/font-awesome.css'?>"/>
    <link rel="stylesheet" href="<?php echo MAGICFORM_URL . "assets/css/datepicker.min.css" ?>" />
    <link rel="stylesheet" href="<?php echo MAGICFORM_URL . "assets/css/general.css" ?>" />
    <link rel="stylesheet" href="<?php echo MAGICFORM_URL . "assets/css/theme.css" ?>" />
    <script type="text/javascript">
        var magicFormSettings = {
            "ajaxUrl": "<?php echo admin_url('admin-ajax.php') ?>"
        };
    </script>
    <script type="text/javascript" src="<?php echo  MAGICFORM_URL . "assets/js/signature.js"?>"></script>
    <script src="<?php echo get_site_url() . "/wp-includes/js/jquery/jquery.js" ?>"></script>
    <script type="text/javascript" src="<?php echo MAGICFORM_URL . "public/js/magicform-public.js" ?>"></script>
    <style>
        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #FFF;
        }
    </style>
</head>
<body>
    <?php
        $formViewer = new MagicForm_Viewer();
        $formViewer->divCount;
        $formViewer->view = "";
        $formViewer->formId = null;
        $formViewer->pageElements = array();
        $formViewer->json = array();
        $formViewer->validations = array();
        $formViewer->allElements = array();
        $formViewer->type = 0;
        $formViewer->submitType = "preview";
    ?>
    <div class="mf-content">
        <?php echo ($formViewer->shortcode($_GET)); ?>
    </div>
    <link rel="stylesheet" href="<?php echo magicform_setFontFamily($formViewer->fontFamily)?>">
    <script type="text/javascript" src="<?php echo MAGICFORM_URL . "assets/js/datepicker.min.js" ?>"></script>
    <script type="text/javascript" src="<?php echo MAGICFORM_URL . "assets/js/datepicker.". $formViewer->language .".js"; ?>"></script>
</body>
</html>