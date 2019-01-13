<?php

require('../fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('courier','B',40);
$pdf->Cell(190,20,'Kidoikoi',0,1,'C');
$pdf->Output();

?>