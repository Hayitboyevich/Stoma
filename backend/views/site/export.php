<?php

/* @var $this yii\web\View */

/* @var $reports Report */

/* @var $data array */

use common\models\Report;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$title = 'Report_' . $data['start_date'] . '_' . $data['end_date'];

$objPHPExcel = new Spreadsheet();
$sheet = 0;

$objPHPExcel->setActiveSheetIndex($sheet);

foreach (range('A', 'U') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle($title);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'ИНВОЙС ID');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'ВИЗИТ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'ДАТА СОЗДАНИЯ ИНВОЙСА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'ДАТА ОПЛАТЫ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'ФИО ПАЦИЕНТА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'ФИО ВРАЧА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'ФИО АССИСТЕНТА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'КАТЕГОРИЯ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'ОКАЗАННАЯ УСЛУГА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'ЦЕНА УСЛУГИ ПО ПРАЙСУ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'ЦЕНА УСЛУГИ ПО ПРАЙСУ СО СКИДКОЙ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, 1, 'КОЛ-ВО');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, 1, 'КОЛ-ВО ЗУБ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, 1, 'ОБЩАЯ СУММА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, 1, 'ВЫПЛАЧЕННАЯ СУММА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, 1, 'ДОЛГ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17, 1, 'ЗАРПЛАТА ВРАЧА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18, 1, 'ЗАРПЛАТА АССИСТЕНТА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19, 1, 'РАСХОДНЫЕ МАТЕРИАЛЫ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20, 1, 'ОСТАТОК');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21, 1, 'РЕНТАБЕЛЬНОСТЬ');

$totalDoctorEarnings = 0;
$totalAssistantEarnings = 0;
$totalConsumable = 0;
$totalRemains = 0;
$lastIndex = 0;
$totalPaidSum = 0;
$totalDebt = 0;
$arrDebts = [];

foreach ($reports as $index => $report) {
    $price = $report->price_with_discount * $report->sub_cat_amount * $report->teeth_amount;

    $arrDebts[$report->invoice_id] = in_array($report->invoice_id, $arrDebts) ? 0 : $report->invoice->getRemains();
    $totalPaidSum += $report->paid_sum;
    $totalDebt += $report->invoice ? $report->invoice->getRemains() : 0;
    $totalDoctorEarnings += $report->doctor_earnings;
    $totalAssistantEarnings += $report->assistant_earnings;
    $totalConsumable += $report->consumable;
    $totalRemains += $report->remains;

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        1,
        $index + 2,
        '#' . $report->invoice_id
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        2,
        $index + 2,
        date('d.m.Y H:i', strtotime($report->visit_time))
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        3,
        $index + 2,
        date('d.m.Y H:i', strtotime($report->invoice ? $report->invoice->created_at : $report->created_at))
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        4,
        $index + 2,
        date('d.m.Y H:i', strtotime($report->created_at))
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        5,
        $index + 2,
        $report->patient ? $report->patient->getFullName() : '-'
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        6,
        $index + 2,
        $report->doctor ? $report->doctor->getFullName() : '-'
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        7,
        $index + 2,
        $report->assistant ? $report->assistant->getFullName() : '-'
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        8,
        $index + 2,
        $report->priceList ? $report->priceList->section : '-'
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        9,
        $index + 2,
        $report->priceListItem ? $report->priceListItem->name : '-'
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        10,
        $index + 2,
        number_format($report->sub_cat_price, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        11,
        $index + 2,
        number_format($report->price_with_discount, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        12,
        $index + 2,
        $report->sub_cat_amount
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        13,
        $index + 2,
        $report->teeth_amount
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        14,
        $index + 2,
        number_format($price, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        15,
        $index + 2,
        number_format($report->paid_sum, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        16,
        $index + 2,
        number_format($price - $report->paid_sum, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        17,
        $index + 2,
        number_format($report->doctor_earnings, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        18,
        $index + 2,
        number_format($report->assistant_earnings, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        19,
        $index + 2,
        number_format($report->consumable, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        20,
        $index + 2,
        number_format($report->remains, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        21,
        $index + 2,
        $report->profitability . '%'
    );

    $objPHPExcel->getActiveSheet()->getStyle('A' . ($index + 2) . ':U' . ($index + 2))->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->applyFromArray(
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
    15,
    $lastIndex + 1,
    number_format($totalPaidSum, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    16,
    $lastIndex + 1,
    number_format(array_sum($arrDebts), 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    17,
    $lastIndex + 1,
    number_format($totalDoctorEarnings, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    18,
    $lastIndex + 1,
    number_format($totalAssistantEarnings, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    19,
    $lastIndex + 1,
    number_format($totalConsumable, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    20,
    $lastIndex + 1,
    number_format($totalRemains, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->getStyle('A' . ($lastIndex + 1) . ':U' . ($lastIndex + 1))->applyFromArray(
    [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'argb' => 'ADD8E6',
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
