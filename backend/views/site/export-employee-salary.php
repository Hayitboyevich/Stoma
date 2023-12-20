<?php

/* @var $this yii\web\View */

/* @var $reports Report */

/* @var $employeeSalary EmployeeSalary */
/* @var $startDate string */
/* @var $endDate string */

use common\models\EmployeeSalary;
use common\models\Report;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$title = 'EmployeeSalary';

$objPHPExcel = new Spreadsheet();
$sheet = 0;

$objPHPExcel->setActiveSheetIndex($sheet);

foreach (range('A', 'K') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle($title);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'ИНВОЙС');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'ПАЦИЕНТ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'КАТЕГОРИЯ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'ДАТА ВИЗИТА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'УСЛУГА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'ЦЕНА УСЛУГИ ПО ПРАЙСУ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'ЦЕНА УСЛУГИ СО СКИДКОЙ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'ОБЩАЯ СУММА');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, 1, 'ПРОЦЕНТ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, 1, 'ЗАРАБОТОК');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, 1, 'ДАТА ОПЛАТЫ');

$totalWithoutDiscount = 0;
$totalWithDiscount = 0;
$totalPrice = 0;
$totalEmployeeEarnings = 0;
$lastIndex = 0;

foreach ($employeeSalary as $index => $item) {
    $totalWithoutDiscount += $item->sub_cat_price;
    $totalWithDiscount += $item->price_with_discount;
    $totalPrice += $item->price_with_discount * $item->teeth_amount * $item->sub_cat_amount;
    $totalEmployeeEarnings += $item->employee_earnings;

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        1,
        $index + 2,
        '#' . $item->invoice_id
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        2,
        $index + 2,
        $item->patient_name
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        3,
        $index + 2,
        $item->cat_title
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        4,
        $index + 2,
        date('d.m.Y', strtotime($item->visit_time))
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        5,
        $index + 2,
        $item->sub_cat_title
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        6,
        $index + 2,
        number_format($item->sub_cat_price, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        7,
        $index + 2,
        number_format($item->price_with_discount, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        8,
        $index + 2,
        number_format($item->price_with_discount * $item->teeth_amount * $item->sub_cat_amount, 0, '', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        9,
        $index + 2,
        $item->cat_percent . '%'
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        10,
        $index + 2,
        number_format($item->employee_earnings, 0, ' ', ' ')
    );

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        11,
        $index + 2,
        date('d.m.Y', strtotime($item->created_at))
    );

    $objPHPExcel->getActiveSheet()->getStyle('A' . ($index + 2) . ':K' . ($index + 2))->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->applyFromArray(
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
    6,
    $lastIndex + 1,
    number_format($totalWithoutDiscount, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    7,
    $lastIndex + 1,
    number_format($totalWithDiscount, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    8,
    $lastIndex + 1,
    number_format($totalPrice, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
    10,
    $lastIndex + 1,
    number_format($totalEmployeeEarnings, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->getStyle('A' . ($lastIndex + 1) . ':K' . ($lastIndex + 1))->applyFromArray(
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
