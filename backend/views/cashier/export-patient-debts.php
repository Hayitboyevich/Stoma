<?php

/* @var $invoices Invoice */

use common\models\Invoice;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$title = 'patientsDebts';

$objPHPExcel = new Spreadsheet();
$sheet = 0;

$objPHPExcel->setActiveSheetIndex($sheet);

foreach (range('A', 'E') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle($title);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'ИНВОЙС №');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'ДАТА ВИЗИТА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'ПАЦИЕНТ ФИО');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'ДОКТОР ФИО');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'ОБЩАЯ СУММА ДОЛГА');

$total = 0;
$lastIndex = 0;

foreach ($invoices as $index => $invoice) {
    $total += $invoice->getRemains();

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        1,
        $index + 2,
        $invoice->getInvoiceNumber()
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        2,
        $index + 2,
        $invoice->getVisit()
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        3,
        $index + 2,
        $invoice->patient ? $invoice->patient->getFullName() : '-'
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        4,
        $index + 2,
        $invoice->doctor ? $invoice->doctor->getFullName() : '-'
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        5,
        $index + 2,
        number_format($invoice->getRemains(), 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->getStyle('A' . ($index + 2) . ':E' . ($index + 2))->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray(
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

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    1,
    $lastIndex + 1,
    'Итого:'
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    5,
    $lastIndex + 1,
    number_format($total, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->getStyle('A' . ($lastIndex + 1) . ':E' . ($lastIndex + 1))->applyFromArray(
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
