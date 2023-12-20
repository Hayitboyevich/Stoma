<?php

/* @var $this yii\web\View */

/* @var $priceListItems PriceListItem */

use common\models\PriceListItem;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$title = 'PriceList_' . date('d-m-Y');

$objPHPExcel = new Spreadsheet();
$sheet = 0;

$objPHPExcel->setActiveSheetIndex($sheet);

foreach (range('A', 'F') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle($title);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, '№');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Имя');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'Категория');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'Применять скидку');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'Расходный материал');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'Цена');


$i = 0;
$numberRow = 1;
foreach ($priceListItems as $index => $priceListItem) {
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        2,
        $i + 2,
        $priceListItem->name
    );

    if (!empty($priceListItem->priceListItems)) {
        $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 2) . ':F' . ($i + 2))->applyFromArray(
            [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'c5d9f1'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]
        );

        foreach ($priceListItem->priceListItems as $subIndex => $subPriceListItem) {
            ++$i;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                1,
                $i + 2,
                $numberRow
            );

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                2,
                $i + 2,
                $subPriceListItem->name
            );

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                3,
                $i + 2,
                $priceListItem->priceList ? $priceListItem->priceList->section : ''
            );

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                4,
                $i + 2,
                $priceListItem->discount_apply ? 'Да' : 'Нет'
            );

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                5,
                $i + 2,
                number_format($priceListItem->consumable, 0, '', ' ')
            );

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                6,
                $i + 2,
                number_format($priceListItem->price, 0, '', ' ')
            );

            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 2) . ':F' . ($i + 2))->applyFromArray(
                [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]
            );

            $numberRow++;
        }
    } else {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
            1,
            $i + 2,
            $numberRow
        );

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
            3,
            $i + 2,
            $priceListItem->priceList ? $priceListItem->priceList->section : ''
        );

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
            4,
            $i + 2,
            $priceListItem->discount_apply ? 'Да' : 'Нет'
        );

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
            5,
            $i + 2,
            number_format($priceListItem->consumable, 0, '', ' ')
        );

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
            6,
            $i + 2,
            number_format($priceListItem->price, 0, '', ' ')
        );

        $numberRow++;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 2) . ':F' . ($i + 2))->applyFromArray(
        [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]
    );

    $i++;
}

$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
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
