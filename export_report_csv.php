<?php

// check if report data is set
if (isset($_POST['report_data'])) {
    $report_data = json_decode($_POST['report_data'], true);
    
    // set headers to force download of file
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');
    
    // open output stream
    $output = fopen('php://output', 'w');
    
    // write headers to file
    fputcsv($output, array_keys($report_data[0]));
    
    // write data to file
    foreach ($report_data as $row) {
        fputcsv($output, $row);
    }
    
    // close output stream
    fclose($output);
    exit;
}
?>
