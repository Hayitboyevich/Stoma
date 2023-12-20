<?php

use common\models\EmployeeSalary;
use common\models\User;
use yii\helpers\Url;

/**@var $employeeSalary EmployeeSalary */
/**@var $user User */
/**@var $startDate string */
/**@var $endDate string */

$this->title = $user->getFullName();

?>
<div class="salarys" style="margin-top: 10px;">
    <div class="salary__wrapper-card">
        <div style="display:flex; justify-content: space-between; align-items: center">
            <h2><?= $user->getFullName() ?></h2>
            <a href="<?= Url::to(
                ['site/excel-employee-salary', 'userId' => $user->id, 'startDate' => $startDate, 'endDate' => $endDate]
            ) ?>">
                <img style="width: 30px; height: 30px;" src="/img/excel.svg" alt="">
            </a>
        </div>
        <div class="salary__wrapper-card-item">
            <div class="card__item-head">
                <div class="card__item-left">
                    <p>Подробнее</p>
                </div>
            </div>

            <div class="card__item-body">
                <div class="card__item-body-item">
                    <div class="item__title">
                        <span>Инвойс</span>
                    </div>
                    <div class="item__title">
                        <span>Пациент</span>
                    </div>
                    <div class="item__title">
                        <span>Категория</span>
                    </div>
                    <div class="item__title">
                        <span>Дата визита</span>
                    </div>
                    <div class="item__title">
                        <span>Услуга</span>
                    </div>
                    <div class="item__title">
                        <span>Цена услуги по прайсу</span>
                    </div>
                    <div class="item__title">
                        <span>Цена услуги со скидкой</span>
                    </div>
                    <div class="item__title">
                        <span>Общая сумма</span>
                    </div>
                    <div class="item__title">
                        <span>Процент</span>
                    </div>
                    <div class="item__title">
                        <span>Заработок</span>
                    </div>
                    <div class="item__title">
                        <span>Дата оплаты</span>
                    </div>
                </div>
                <?php
                    $totalWithoutDiscount = 0;
                    $totalWithDiscount = 0;
                    $totalPrice = 0;
                    $totalEmployeeEarnings = 0;
                ?>
                <?php foreach ($employeeSalary as $item): ?>
                    <?php

                        $totalWithoutDiscount += $item->sub_cat_price;
                        $totalWithDiscount += $item->price_with_discount;
                        $totalPrice += $item->price_with_discount * $item->teeth_amount * $item->sub_cat_amount;
                        $totalEmployeeEarnings += $item->employee_earnings;

                    ?>
                    <div class="card__item-body-item">
                        <div class="item">
                            <span>#<?= $item->invoice_id ?></span>
                        </div>
                        <div class="item">
                            <span>
                                <a href="<?= Url::to(['patient/update', 'id' => $item->patient_id]) ?>">
                                    <?= $item->patient_name ?>
                                </a>
                            </span>
                        </div>
                        <div class="item">
                            <span><?= $item->cat_title ?></span>
                        </div>
                        <div class="item">
                            <span><?= date('d.m.Y', strtotime($item->visit_time)) ?></span>
                        </div>
                        <div class="item">
                            <span><?= $item->sub_cat_title ?></span>
                        </div>
                        <div class="item">
                            <span><?= number_format($item->sub_cat_price, 0, '', ' ') ?> сум</span>
                        </div>
                        <div class="item">
                            <span><?= number_format($item->price_with_discount, 0, '', ' ') ?> сум</span>
                        </div>
                        <div class="item">
                            <span><?= number_format($item->price_with_discount * $item->teeth_amount * $item->sub_cat_amount, 0, '', ' ') ?> сум</span>
                        </div>
                        <div class="item">
                            <span><?= $item->cat_percent ?>%</span>
                        </div>
                        <div class="item">
                            <span><?= number_format($item->employee_earnings, 0, ' ', ' ') ?> сум</span>
                        </div>
                        <div class="item">
                            <span><?= date('d.m.Y', strtotime($item->created_at)) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="card__item-body-item bg__blue">
                    <div class="item">
                        <span>Итого:</span>
                    </div>
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item">
                        <span><?= number_format($totalWithoutDiscount, 0, ' ', ' ') ?> сум</span>
                    </div>
                    <div class="item">
                        <span><?= number_format($totalWithDiscount, 0, ' ', ' ') ?> сум</span>
                    </div>
                    <div class="item">
                        <span><?= number_format($totalPrice, 0, ' ', ' ') ?> сум</span>
                    </div>
                    <div class="item"></div>
                    <div class="item">
                        <span><?= number_format($totalEmployeeEarnings, 0, ' ', ' ') ?> сум</span>
                    </div>
                    <div class="item"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>


    .salarys {
        height: 100%;
        padding-bottom: 20px;
    }

    .salarys .salary__top {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 12px 20px 12px 20px;
        -webkit-box-shadow: inset 0px -1px 0px rgba(21, 24, 48, 0.08);
        box-shadow: inset 0px -1px 0px rgba(21, 24, 48, 0.08);
    }

    .salarys .salary__top .top_select {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .salarys .salary__top .top_select .select_wrapper {
        width: 180px;
        position: relative;
    }

    .salarys .salary__top .top_select .select_wrapper .select {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border: 1px solid #f2f6fa;
        border-radius: 6px;
        padding: 6px 8px;
    }

    .salarys .salary__top .top_select .select_wrapper .select .select__dropdown {
        width: 100%;
        margin: 0 10px 0 10px;
    }

    .salarys .salary__top .top_select .select_wrapper .select .select__dropdown span {
        font-weight: 400;
        font-size: 10px;
        line-height: 12px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--grey-color);
    }

    .salarys .salary__top .top_select .select_wrapper .select .select__dropdown p {
        margin: 0;
        font-weight: 600;
        font-size: 12px;
        line-height: 16px;
        letter-spacing: 0.02em;
        color: #00154a;
    }

    .salarys .salary__top .top_select .select_wrapper .select:hover {
        cursor: pointer;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option15 {
        position: absolute;
        border-right: 1px solid #f2f6fa;
        border-left: 1px solid #f2f6fa;
        border-bottom: 1px solid #f2f6fa;
        width: 100%;
        top: 100%;
        bottom: -100%;
        height: auto;
        left: 0;
        z-index: 10;
        visibility: hidden;
        background-color: #fff;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option15 .select_option_item {
        background-color: #fff;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option15 p {
        margin: 0;
        font-weight: 600;
        font-size: 14px;
        line-height: 16px;
        letter-spacing: 0.02em;
        color: #00154a;
        padding: 15px 10px 10px 24px;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option15 p:hover {
        cursor: pointer;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option16 {
        position: absolute;
        border-right: 1px solid #f2f6fa;
        border-left: 1px solid #f2f6fa;
        border-bottom: 1px solid #f2f6fa;
        width: 100%;
        top: 100%;
        bottom: -100%;
        height: auto;
        left: 0;
        z-index: 10;
        visibility: hidden;
        background-color: #fff;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option16 .select_option_item {
        background-color: #fff;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option16 p {
        margin: 0;
        font-weight: 600;
        font-size: 14px;
        line-height: 16px;
        letter-spacing: 0.02em;
        color: #00154a;
        padding: 15px 10px 10px 24px;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option16 p:hover {
        cursor: pointer;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option17 {
        position: absolute;
        border-right: 1px solid #f2f6fa;
        border-left: 1px solid #f2f6fa;
        border-bottom: 1px solid #f2f6fa;
        width: 100%;
        top: 100%;
        bottom: -100%;
        height: auto;
        left: 0;
        z-index: 10;
        visibility: hidden;
        background-color: #fff;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option17 .select_option_item {
        background-color: #fff;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option17 p {
        margin: 0;
        font-weight: 600;
        font-size: 14px;
        line-height: 16px;
        letter-spacing: 0.02em;
        color: #00154a;
        padding: 15px 10px 10px 24px;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option17 p:hover {
        cursor: pointer;
    }

    .salarys .salary__top .top_select .select_wrapper .select_option_active {
        visibility: visible;
    }

    .salarys .salary__top .top_select .select_wrapper:not(:last-child) {
        margin-right: 12px;
    }

    @media screen and (max-width: 1366px) {
        .salarys .salary__top .top_select .select_wrapper {
            width: 140px;
            position: relative;
        }

        .salarys .salary__top .top_select .select_wrapper .select_option15 p {
            padding: 10px;
        }

        .salarys .salary__top .top_select .select_wrapper .select_option16 p {
            padding: 10px;
        }

        .salarys .salary__top .top_select .select_wrapper .select_option17 p {
            padding: 10px;
        }
    }

    .salarys .salary__wrapper-card {
        display: -ms-grid;
        /*display: grid;*/
        -ms-grid-columns: (1fr) [ 2 ];
        grid-template-columns: repeat(2, 1fr);
        -ms-grid-rows: (1fr) [ 2 ];
        grid-template-rows: repeat(2, 1fr);
        grid-column-gap: 5px;
        grid-row-gap: 10px;
        margin-top: 18px;
        padding: 0 20px 0 20px;
        /*height: calc(100% - 105px);*/
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item {
        height: 100%;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        border: 1px solid #eef0f3;
        border-radius: 4px;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-head {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        background-color: #eef0f3;
        padding: 5px 15px;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-head .card__item-left p {
        margin: 0;
        font-weight: 600;
        font-size: 12px;
        line-height: 20px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #00288d;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-head .card__item-right {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-head .card__item-right img:hover {
        cursor: pointer;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-head .card__item-right img:not(:last-child) {
        margin-right: 20px;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body {
        border: 1px solid #eef0f3;
        border-radius: 4px;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .card__item-body-item {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 100%;
        border-bottom: 1px solid #f2f6fa;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .card__item-body-item .item__title {
        padding: 4px 8px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        background-color: #f8f8f9;
        -webkit-box-shadow: inset 1px 0 0 #e8e8ee;
        box-shadow: inset 1px 0 0 #e8e8ee;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .card__item-body-item .item__title span {
        font-weight: 600;
        font-size: 11px;
        line-height: 16px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #757d93;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .card__item-body-item .item {
        padding: 4px 8px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .card__item-body-item .item span {
        font-weight: 600;
        font-size: 11px;
        line-height: 16px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #1F2128;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .card__item-body-item:last-child {
        background: #00288d;
        /* Цвет фона */
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .bg__blue {
        background-color: #00288d;
    }

    .salarys .salary__wrapper-card .salary__wrapper-card-item .card__item-body .bg__blue .item span {
        color: #fff;
    }

</style>

