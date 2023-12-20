<?php

/** @var Report $reports */
/** @var $data array */

use common\models\Report;
use yii\helpers\Url;

$this->title = 'Статистика';

?>
<div class="tableWrapperContainer">
    <h3 class="wrapper__box-title">Подробный отчет </h3>
    <div class="statistics_employee-select">
        <form class="statistics_employee-select-left" action="<?= Url::to(['site/report']) ?>">
            <label class="statistics_employee-select-left-label">
                с
                <input type="date" name="startDate" value="<?= $data['start_date'] ?>">
            </label>
            <label class="statistics_employee-select-left-label">
                по
                <input type="date" name="endDate" value="<?= $data['end_date'] ?>">
            </label>
            <button type="submit" class="btn-reset btn-show">показать</button>
        </form>
        <div class="statistics_employee-select-right">
            <a href="<?= Url::to(
                ['site/export', 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]
            ) ?>" class="btn-reset btn__schedule">
                экспортировать
                <img src="/img/svg/excel_white.svg" alt="">
            </a>
        </div>
    </div>

    <div class="secondaryContainer">
        <table style="width: 100%; border-collapse: collapse; border: none" >
            <thead cellspacing="0" cellpadding="0">
            <tr class="headerBackground">
                <th>ИНВОЙС ID</th>
                <th>ВИЗИТ</th>
                <th>ДАТА СОЗДАНИЯ ИНВОЙСА</th>
                <th>ДАТА ОПЛАТЫ</th>
                <th>ФИО ПАЦИЕНТА</th>
                <th>ФИО ВРАЧА</th>
                <th>ФИО <br> АССИСТЕНТА</th>
                <th>ОКАЗАННАЯ УСЛУГА</th>
                <th>ЦЕНА УСЛУГИ ПО ПРАЙСУ</th>
                <th>ЦЕНА УСЛУГИ ПО ПРАЙСУ <br> СО СКИДКОЙ</th>
                <th>КОЛ-ВО</th>
                <th>КОЛ-ВО <br> ЗУБ</th>
                <th>ОБЩАЯ СУММА</th>
                <th>ВЫПЛАЧЕННАЯ СУММА</th>
                <th>ДОЛГ</th>
                <th>ЗАРПЛАТА <br>ВРАЧА</th>
                <th>ЗАРПЛАТА <br> АССИСТЕНТА</th>
                <th>ЗАРПЛАТА <br> ТЕХНИКА</th>
                <th>УСЛУГА ТЕХНИКА</th>
                <th>ЦЕНА УСЛУГИ ТЕХНИКА</th>
                <th>РАСХОДНЫЕ <br> МАТЕРИАЛЫ</th>
                <th>ОСТАТОК</th>
                <th>РЕНТАБЕЛЬНОСТЬ</th>
            </tr>
            </thead>
            <tbody class="bodyTable" cellspacing="0" cellpadding="0">

            <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="24" style="text-align: center">Нет данных</td>
                </tr>
            <?php endif; ?>

            <?php
                $totalDoctorEarnings = 0;
                $totalAssistantEarnings = 0;
                $totalTechnicianEarnings = 0;
                $totalConsumable = 0;
                $totalRemains = 0;
                $totalPaidSum = 0;
                $totalDebt = 0;
                $arrDebts = [];
            ?>

            <?php foreach ($reports as $report): ?>
                <?php
                $arrDebts[$report->invoice_id] = in_array($report->invoice_id, $arrDebts) ? 0 : $report->invoice->getRemains();
                $price = $report->price_with_discount * $report->sub_cat_amount * $report->teeth_amount;
                $totalPaidSum += $report->paid_sum;
                $totalDebt += $report->invoice ? $report->invoice->getRemains() : 0;
                $totalDoctorEarnings += $report->doctor_earnings;
                $totalAssistantEarnings += $report->assistant_earnings;
                $totalTechnicianEarnings += $report->technician_earnings;
                $totalConsumable += $report->consumable;
                $totalRemains += $report->remains;
                ?>
                <tr>
                    <td>
                        <?= $report->invoice ? '#' . $report->invoice->id : '-' ?>
                    </td>
                    <td>
                        <?= date('d.m.Y H:i', strtotime($report->visit_time)) ?>
                    </td>
                    <td>
                        <?= date('d.m.Y H:i', strtotime($report->invoice ? $report->invoice->created_at : $report->created_at)) ?>
                    </td>
                    <td>
                        <?= date('d.m.Y H:i', strtotime($report->created_at)) ?>
                    </td>
                    <td>
                        <?= $report->patient
                            ? "<a href='" . Url::to(['patient/update', 'id' => $report->patient_id]) . "'>" . $report->patient->getFullName() . "</a>"
                            : '-' ?>
                    </td>
                    <td>
                        <?= $report->doctor ? $report->doctor->getFullName() : '-' ?>
                    </td>
                    <td>
                        <?= $report->assistant ? $report->assistant->getFullName() : '-' ?>
                    </td>
                    <td>
                        <?= $report->priceListItem ? $report->priceListItem->name : '-' ?>
                    </td>
                    <td>
                        <?= number_format($report->sub_cat_price, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->price_with_discount, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= $report->sub_cat_amount ?>
                    </td>
                    <td>
                        <?= $report->teeth_amount ?>
                    </td>
                    <td>
                        <?= number_format($price, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->paid_sum, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($price - $report->paid_sum, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->doctor_earnings, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->assistant_earnings, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->technician_earnings ?? 0, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= $report->technicianPriceList ? $report->technicianPriceList->name : '-' ?>
                    </td>
                    <td>
                        <?= number_format($report->tech_cat_price, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->consumable, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= number_format($report->remains, 0, '', ' ') . ' сум' ?>
                    </td>
                    <td>
                        <?= $report->profitability . '%' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="secondaryContainerFooter">
            <div>
                <p class="footerInfo">ИТОГО</p>
            </div>
            <div class="footerCardWrapper">
                <div class="footerCard">
                    <p class="footerTitle">ОБЩАЯ ВЫПЛАЧЕННАЯ СУММА</p>
                    <p class="footerSubTitle">
                        <?= number_format($totalPaidSum, 0, '', ' ') ?> сум
                    </p>
                </div>
                <div class="footerCard">
                    <p class="footerTitle">ОБЩАЯ СУММА ДОЛГОВ</p>
                    <p class="footerSubTitle">
                        <?= number_format(array_sum($arrDebts), 0, '', ' ') ?> сум
                    </p>
                </div>
                <div class="footerCard">
                    <p class="footerTitle">ЗАРПЛАТА ВРАЧА</p>
                    <p class="footerSubTitle">
                        <?= number_format($totalDoctorEarnings, 0, '', ' ') ?> сум
                    </p>
                </div>
                <div class="footerCard">
                    <p class="footerTitle">ЗАРПЛАТА АССИСТЕНТА</p>
                    <p class="footerSubTitle">
                        <?= number_format($totalAssistantEarnings, 0, '', ' ') ?> сум
                    </p>
                </div>
                <div class="footerCard">
                    <p class="footerTitle">ЗАРПЛАТА ТЕХНИКА</p>
                    <p class="footerSubTitle">
                        <?= number_format($totalTechnicianEarnings, 0, '', ' ') ?> сум
                    </p>
                </div>
                <div class="footerCard">
                    <p class="footerTitle">РАСХОДНЫЕ МАТЕРИАЛЫ</p>
                    <p class="footerSubTitle">
                        <?= number_format($totalConsumable, 0, '', ' ') ?> сум
                    </p>
                </div>
                <div class="footerCard">
                    <p class="footerTitle">ОСТАТОК</p>
                    <p class="footerSubTitle">
                        <?= number_format($totalRemains, 0, '', ' ') ?> сум
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
