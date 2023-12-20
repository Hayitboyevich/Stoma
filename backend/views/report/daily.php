<?php

/** @var array $params */
use common\models\DailyReport;
$dailyReports = new DailyReport(['date' => $params['date']]);
?>
<div class="report">
    <?= $this->render('_report-top',['params' => $params]); ?>
    <div class="report__wrapper-card">
        <?= $this->render('daily/visits',['params' => $params,'dailyReports' => $dailyReports]); ?>
        <?= $this->render('daily/finance',['params' => $params,'dailyReports' => $dailyReports]); ?>
        <?= $this->render('daily/new-patients',['params' => $params,'dailyReports' => $dailyReports]); ?>
        <?= $this->render('daily/return-visits',['params' => $params,'dailyReports' => $dailyReports]); ?>
        <?= $this->render('daily/cancelled-accounts',['params' => $params,'dailyReports' => $dailyReports]); ?>
    </div>

</div>