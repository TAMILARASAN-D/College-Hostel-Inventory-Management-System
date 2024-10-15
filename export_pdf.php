<?php
require('fpdf185/fpdf.php');
require('db_connect.php');

// get data from database
$result = mysqli_query($conn, "SELECT * FROM purchase");

// create new PDF document
$pdf = new FPDF();
$pdf->AddPage();

// set table header
$pdf->SetFont('Arial','B',12);
$pdf->Cell(40,10,'Date',1);
$pdf->Cell(80,10,'Product',1);
$pdf->Cell(40,10,'Quantity',1);
$pdf->Cell(40,10,'Price',1);
$pdf->Ln();

// Replace "your_font_file.ttf" with the name of your font file
$pdf->AddFont('Noto Sans Tamil','','NotoSansTamil-Regular.php',true);


// set table rows
$pdf->SetFont('Noto Sans Tamil','',12);
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(40,10,$row['date'],1);
    $pdf->Cell(80,10,$row['item_name'],1);
    $pdf->Cell(40,10,$row['quantity'],1);
    $pdf->Cell(40,10,$row['price'],1);
    $pdf->Ln();
}

// output the PDF
$pdf->Output();
?>
