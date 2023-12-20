<?php

/* @var $users User */

/* @var $data array */

use common\models\User;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$title = 'employees_' . $data['start_date'] . '_' . $data['end_date'];

$objPHPExcel = new Spreadsheet();
$sheet = 0;

$objPHPExcel->setActiveSheetIndex($sheet);

foreach (range('A', 'D') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle($title);

$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'СОТРУДНИК');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'ДОЛЖНОСТЬ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'КОЛ-ВО ПАЦИЕНТОВ');
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'ЗАРПЛАТА');

$total = 0;
$lastIndex = 0;
$footerTotal = 0;

foreach ($users as $index => $user) {
    $total = User::getTotalEarnings($user->id, $data['start_date'], $data['end_date']);
    $footerTotal += $total;

    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        1,
        $index + 2,
        $user->getFullName()
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        2,
        $index + 2,
        User::USER_ROLE[$user->role]
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        3,
        $index + 2,
        User::getCountPatients($user->id, $data['start_date'], $data['end_date'])
    );
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
        4,
        $index + 2,
        number_format($total ?? 0, 0, ' ', ' ')
    );

    $objPHPExcel->getActiveSheet()->getStyle('A' . ($index + 2) . ':D' . ($index + 2))->applyFromArray(
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

$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(
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
    4,
    $lastIndex + 1,
    number_format($footerTotal, 0, '', ' ')
);

$objPHPExcel->getActiveSheet()->getStyle('A' . ($lastIndex + 1) . ':D' . ($lastIndex + 1))->applyFromArray(
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
