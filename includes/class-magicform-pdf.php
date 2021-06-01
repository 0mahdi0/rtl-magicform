<?php
/**
 * Generate Pdf
 */

require_once MAGICFORM_PATH . "/includes/libs/tfpdf/tfpdf.php";

class MagicForm_PDF extends tFPDF
{
    public $submission_id;
    public $form_name;
    public $date;
    public $action;

    function Header()
    {

        if (!empty($this->action) && $this->action->payload->logo != "") {
            $this->Image($this->action->payload->logo, 10, 10, 50);
        }
        // Move to the right
        $this->Cell(55);

        // Add a Unicode font (uses UTF-8)
        $this->SetFont('DejaVu', '', 10);

        if(!empty($this->action)){
            $this->MultiCell(0, 5, $this->action->payload->header, 0, 1);
        }
       
        // Line break

        $this->Ln(20);
        $this->SetFont('DejaVu', '', 18);
        $this->Cell(100, 10, isset($this->title)?$this->title:"", 0);

        $this->SetFont('DejaVu', '', 10);
        $this->SetTextColor(150, 150, 150);
        
        if(isset($this->action->payload->timeVisible))
            $this->Cell(0, 5, "Date: " . $this->date, 0, 1, "R");

        if(isset($this->action->payload->idVisible))
            $this->Cell(0, 5, "Submission Id: " . $this->submission_id, 0, 1, "R");
            
        if(isset($this->action->payload->formNameVisible))
            $this->Cell(0, 5, "Form: " . $this->form_name, 0, 1, "R");
            
        $this->Ln();
    }


    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);

        // Arial italic 8
        $this->SetFont('DejaVu', '', 8);
        // Text color in gray
        $this->SetTextColor(128);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function FormDataPrint($formData, $allElements)
    {
        foreach ($formData as $field_id => $field_value) {
            $field_name = isset($allElements[$field_id]) ? $allElements[$field_id]->payload->labelText : $field_id;
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetFont('DejaVu', 'B', 10);
            $this->SetTextColor(150, 150, 150);
            $numberOfLines = strlen($field_name) > 0 ? ceil(strlen($field_name) / 30) : 1;
            $h = 5 * $numberOfLines;

            $this->MultiCell(60, 6, $field_name, 0, "L");

            $this->SetXY($x + 60, $y);
            $this->SetFont('DejaVu', '', 10);
            $this->SetTextColor(41, 41, 41);

            if (strpos($field_value, "<img") === 0) {
                preg_match("/src='([^']*)'/i", $field_value, $match);
                $pic = $this->getImage($match[1]);
                if ($pic !== false){
                    $this->Image($pic[0], $x +60, $y, 50, 0, $pic[1]);
                    $h+=15;
                }else {
                    $h+=5;
                }
            } else {
                $this->MultiCell(0, 6,  $field_value, 0, "L");
            }

            $this->Ln($h);
            $this->SetDrawColor(204, 204, 204);
            $this->Line(10, $this->getY(), 200, $this->getY());
            $this->Ln(3);
            if($this->GetY()>=265)
             $this->AddPage();
        }

        if(!empty($this->action)){
            $this->Ln();
            $this->SetFont('DejaVu', '', 10);
            $this->MultiCell(0, 5, $this->action->payload->footer, 0, "L");
        }
    }


    function getImage($dataURI)
    {
        $img = explode(',', $dataURI, 2);
        $pic = 'data://text/plain;base64,' . $img[1];
        $type = explode("/", explode(':', substr($dataURI, 0, strpos($dataURI, ';')))[1])[1]; // get the image type
        if ($type == "png" || $type == "jpeg" || $type == "gif") return array($pic, $type);
        return false;
    }
}