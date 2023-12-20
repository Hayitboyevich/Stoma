<?php

/**@var $data array */

/**@var $users User */

use common\models\User;
use yii\helpers\Url;

$this->title = 'Статистика';

?>

<div class="statistics_employee">
    <h3 class="statistics_employee-title">Статистика</h3>
    <div class="statistics_employee-select">
        <form class="statistics_employee-select-left" action="<?= Url::to(['site/statistics']) ?>">
            <label class="statistics_employee-select-left-label">
                с
                <input type="date" name="start_date" value="<?= $data['start_date'] ?>">
            </label>
            <label class="statistics_employee-select-left-label">
                по
                <input type="date" name="end_date" value="<?= $data['end_date'] ?>">
            </label>
            <button type="submit" class="btn-reset btn-show">показать</button>
            <a href="<?= Url::to(['site/statistics', 'start_date' => date('Y-m-d'), 'end_date' => date('Y-m-d')]) ?>"
               class="btn-reset btn__schedule active">
                Сегодня
            </a>
        </form>
        <div class="statistics_employee-select-right">
            <a href="<?= Url::to(['site/report', 'startDate' => $data['start_date'], 'endDate' => $data['end_date']]
            ) ?>"
               class="btn-reset statistics_btn">
                статистика
            </a>
        </div>
    </div>
    <div class="statistics_employee-boxes">
        <h3 class="statistics_employee-boxes-title">Финансовые доход/расход</h3>
        <div class="statistics_employee-boxes-wrapper">
            <div class="statistics_employee-boxes-wrapper-item">
                <div class="statistics_employee-boxes-wrapper-item-left">
                    <img src="/img/scheduleNew/statistika3.png" alt="" width="40px">
                    <h3 class="statistics_employee-boxes-wrapper-item-left-title">Общая сумма по созданным чекам</h3>
                </div>
                <div class="statistics_employee-boxes-wrapper-item-right">
                    <p class="statistics_employee-boxes-wrapper-item-right-number">
                        <?= number_format($data['invoices_total'], 0, ' ', ' ') ?> сум
                    </p>
                </div>
            </div>

            <div class="statistics_employee-boxes-wrapper-item">
                <div class="statistics_employee-boxes-wrapper-item-left">
                    <img src="/img/scheduleNew/statistika7.png" alt="" width="40px">
                    <h3 class="statistics_employee-boxes-wrapper-item-left-title">Прибыль</h3>
                </div>
                <div class="statistics_employee-boxes-wrapper-item-right">
                    <p class="statistics_employee-boxes-wrapper-item-right-number">
                        <?= number_format($data['total_profit'], 0, ' ', ' ') ?> сум
                    </p>
                </div>
            </div>

            <div class="statistics_employee-boxes-wrapper-item">
                <div class="statistics_employee-boxes-wrapper-item-left">
                    <img src="/img/scheduleNew/statistika10.png" alt="" width="40px">
                    <h3 class="statistics_employee-boxes-wrapper-item-left-title">Общее количество записей</h3>
                </div>
                <div class="statistics_employee-boxes-wrapper-item-right">
                    <p class="statistics_employee-boxes-wrapper-item-right-number">
                        <?= $data['visits'] ?>
                    </p>
                </div>
            </div>

            <div class="statistics_employee-boxes-wrapper-item">
                <div class="statistics_employee-boxes-wrapper-item-left">
                    <img src="/img/scheduleNew/statistika8.png" alt="" width="40px">
                    <h3 class="statistics_employee-boxes-wrapper-item-left-title">Ежедневный средний чек</h3>
                </div>
                <div class="statistics_employee-boxes-wrapper-item-right">
                    <p class="statistics_employee-boxes-wrapper-item-right-number">
                        <?= number_format($data['average_check'], 0, ' ', ' ') ?> сум
                    </p>
                </div>
            </div>

            <div class="statistics_employee-boxes-wrapper-item">
                <div class="statistics_employee-boxes-wrapper-item-left">
                    <img src="/img/scheduleNew/statistika5.png" alt="" width="40px">
                    <h3 class="statistics_employee-boxes-wrapper-item-left-title">Общая сумма зарплат</h3>
                </div>
                <div class="statistics_employee-boxes-wrapper-item-right">
                    <p class="statistics_employee-boxes-wrapper-item-right-number">
                        <?= number_format($data['total_salary'], 0, ' ', ' ') ?>
                        сум - <span style="color: #FF6700">(<?= $data['total_salary_percent'] ?>%)</span></p>
                </div>
            </div>

            <div class="statistics_employee-boxes-wrapper-item">
                <div class="statistics_employee-boxes-wrapper-item-left">
                    <img src="/img/scheduleNew/statistika9.png" alt="" width="40px">
                    <h3 class="statistics_employee-boxes-wrapper-item-left-title">
                        Расход по количеству отправленных СМС-оповещений
                    </h3>
                </div>
                <div class="statistics_employee-boxes-wrapper-item-right">
                    <p class="statistics_employee-boxes-wrapper-item-right-number">
                        <?= $data['total_sms_sent'] ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="statistics-employee-bottom">
            <a href="<?= Url::to(['site/paid-patients', 'startDate' => $data['start_date'], 'endDate' => $data['end_date']]) ?>" class="statistics-employee-bottom-card">
                <div class="statistics-employee-bottom-card-img">
                    <img src="/img/scheduleNew/statistika11.png" alt="">
                    <p class="statistics-employee-bottom-card-title">
                        Общая сумма по оплаченным чекам
                    </p>
                </div>
                <div class="statistics-employee-bottom-card-info">
                    <p class="statistics-employee-bottom-card-number">
                        <?= number_format($data['paid_invoices_total'], 0, ' ', ' ') ?> сум
                    </p>
                </div>
            </a>
            <a href="<?= Url::to(
                ['cashier/patient-debts', 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]
            ) ?>" class="statistics-employee-bottom-card">
                <div class="statistics-employee-bottom-card-img">
                    <img src="/img/scheduleNew/statistika12.png" alt="">
                    <p class="statistics-employee-bottom-card-title">
                        Общая сумма долгов
                    </p>
                </div>
                <div class="statistics-employee-bottom-card-info">
                    <p class="statistics-employee-bottom-card-number">
                        <?= number_format($data['total_debt'], 0, ' ', ' ') ?> сум
                    </p>
                </div>
            </a>
            <a href="<?= Url::to(['site/balance-patients']) ?>" class="statistics-employee-bottom-card">
                <div class="statistics-employee-bottom-card-img">
                    <img src="/img/scheduleNew/statistika13.png" alt="">
                    <p class="statistics-employee-bottom-card-title">
                        Сумма авансовых денег
                    </p>
                </div>
                <div class="statistics-employee-bottom-card-info">
                    <p class="statistics-employee-bottom-card-number">
                        <?= number_format($data['total_balance'], 0, ' ', ' ') ?> сум
                    </p>
                </div>
            </a>
        </div>
    </div>

    <div class="salary__wrapper-excel">
        <h3 class="salary__wrapper-excel-title">Таблица</h3>
        <a href="<?= Url::to(
            ['site/excel-all-employee-salary', 'startDate' => $data['start_date'], 'endDate' => $data['end_date']]
        ) ?>"
           class="salary_export_excel_btn">
            экспортировать
            <img src="/img/svg/excel_white.svg" alt="excel">
        </a>
    </div>
    <div class="salary__wrapper-card">
        <div class="salary__wrapper-card-item">
            <div class="card__item-head">
                <div class="card__item-left">
                    <p>ЗАРПЛАТА</p>
                </div>
                <div class="card__item-right"></div>
            </div>

            <div class="card__item-body">
                <div class="card__item-body-item">
                    <div class="item__title">
                        <span>Сотрудник</span>
                    </div>
                    <div class="item__title">
                        <span>Должность</span>
                    </div>
                    <div class="item__title">
                        <span>Кол-во пациентов</span>
                    </div>
                    <div class="item__title">
                        <span>Зарплата</span>
                    </div>
                </div>

                <?php
                $footerTotal = 0;
                foreach ($users as $employee): ?>
                    <?php

                    $total = User::getTotalEarnings($employee->id, $data['start_date'], $data['end_date']);
                    $footerTotal += $total;

                    ?>
                    <a target="_blank" href="<?= Url::to(
                        [
                            'site/view-earnings',
                            'userId' => $employee->id,
                            'startDate' => $data['start_date'],
                            'endDate' => $data['end_date']
                        ]
                    ) ?>"
                       class="card__item-body-item">
                        <div class="item">
                            <span><?= $employee->getFullName() ?></span>
                        </div>
                        <div class="item">
                            <span><?= User::USER_ROLE[$employee->role] ?></span>
                        </div>
                        <div class="item">
                            <span>
                                <?= User::getCountPatients($employee->id, $data['start_date'], $data['end_date']) ?>
                            </span>
                        </div>
                        <div class="item">
                            <span><?= number_format($total ?? 0, 0, ' ', ' ') ?> сум</span>
                        </div>
                    </a>
                <?php
                endforeach; ?>

                <div class="card__item-body-item bg__blue">
                    <div class="item">
                        <span>Итого:</span>
                    </div>
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item">
                        <span><?= number_format($footerTotal, 0, ' ', ' ') ?> сум</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


