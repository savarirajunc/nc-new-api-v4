<!doctype>
<html>
<head>
</head>
<body>
<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$tmpfname = "sample_import.xlsx";
        $excelReader = PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpfname);
        $excelObj = $excelReader->load($tmpfname);
        $worksheet = $excelObj->getSheet(0);//
        $lastRow = $worksheet->getHighestRow();

        $data = [];
        for ($row = 1; $row <= $lastRow; $row++) {
             $data[] = [
                'A' => $worksheet->getCell('A'.$row)->getValue(),
                'B' => $worksheet->getCell('B'.$row)->getValue(),
                'C' => $worksheet->getCell('C'.$row)->getValue(),
                'D' => $worksheet->getCell('D'.$row)->getValue(),
                'E' => $worksheet->getCell('E'.$row)->getValue()
             ];
        }

echo json_encode($data);

?>

</body>
</html>
