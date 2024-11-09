<?php
if (isset($_POST['export'])) {
    require 'Tracker.php';

    $workday = new Tracker ($pdo);
    $recordsinfo = $workday->getAllRecords();

    $filename = 'Daily.csv';

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    header('Pragma: no-cache');
    header('Expires: 0');
    

    $output = fopen('php://output', 'w');

    ob_clean();

    $columns = ['#', 'Ism', 'Kelgan', 'Ketgan', 'Qarzdorlik'];
    fputcsv($output, $columns);


    $i = 0;

    
    foreach($recordsinfo as $recordinfo){
        $i++;
        $recordinfo['required_of']=gmdate('H:i',$recordinfo['required_of']);
        $recordinfo['id']=$i;
        fputcsv($output,$recordinfo);
    }
    
    fclose($output);
    exit;
}
?>
