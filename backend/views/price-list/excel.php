<?php

/* @var $this yii\web\View */

/* @var $priceLists PriceList */

use common\models\PriceList;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$title = 'Category_' . date('d-m-Y');

$objPHPExcel = new Spreadsheet();
$sheet = 0;

$objPHPExcel->setActiveSheetIndex($sheet);

foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle($title);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, '№');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Имя');


foreach ($priceLists as $index => $priceList) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        1,
        $index + 2,
        $index + 1
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        2,
        $index + 2,
        $priceList->section
    );

    $objPHPExcel->getActiveSheet()->getStyle('A' . ($index + 2) . ':B' . ($index + 2))->applyFromArray(
        [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]
    );

    $lastIndex = $index + 2;
}

$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray(
    [
        'font' => [
            'bold' => true,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'FFA500',
            ],
        ],
    ]
);

$filename = $title . ".xls";
header('Content-Disposition: attachment;filename=' . urlencode($filename) . ' ');
header('Content-Type: application/vnd.ms-excel');
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
$objWriter->save('php://output');
