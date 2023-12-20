<?php

/* @var $model Patient; */
/* @var $invoice Invoice; */
/* @var $record Reception */
/* @var $assistants User */
/* @var $doctors User */

use common\models\Invoice;
use common\models\Patient;
use common\models\Reception;
use common\models\User;

$invoiceModel = $invoice;
?>
<input type="hidden" name="patient_id" value="<?= $model->id ?>"/>
<div id="account" class="accounts-patients ">
    <div class="accounts-patients_left">
        <div class="accounts-patients_left_top table-top">
            <div class="accounts-patients_left_head">
                <p>Предварительные счета</p>
                <span style="visibility: hidden;">
                    <img src="/img/excel.svg" alt="">
                </span>
            </div>
            <div class="accounts-patients_left_table sidebar-invoices-wrap">
                <div class="table-head">
                    <span>Счет №</span>
                    <span>Дата создания</span>
                    <span>Зубы</span>
                    <span>Сумма</span>
                </div>
                <?php
                $preliminary_bills = $model->getFormattedInvoices(['preliminary' => Invoice::PRELIMINARY]);
                $preliminary_bills_sum = 0;
                ?>
                <?php if (!empty($preliminary_bills)): ?>
                    <?php foreach ($preliminary_bills as $invoice): $preliminary_bills_sum += $invoice['total_price']; ?>
                        <div class="table-body invoice-details-btn" data-id="<?= $invoice['id'] ?>">
                            <div class="body-item">
                                <span>#<?= $invoice['id'] ?></span>
                            </div>
                            <div class="body-item">
                                <?php
                                $invoice_date = explode(' ', $invoice['created_at']); ?>
                                <span><?= date('d.m.Y', strtotime($invoice_date[0])) ?></span>
                                <span><?= date('H:i', strtotime($invoice_date[1])) ?></span>
                            </div>
                            <div class="body-item">
                                <span><?= $invoice['all_teeth'] ?></span>
                            </div>
                            <div class="body-item">
                                <span><?= number_format($invoice['total_price'], 0, '', ' ') ?> сум</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="accounts-patients_left_add" style="visibility: hidden;">
                <button type="submit">
                    <img src="/img/plus.svg" alt="">
                </button>
            </div>
        </div>
        <div class="accounts-patients_left_top table-body">
            <div class="accounts-patients_left_head">
                <p>счета</p>
                <span style="visibility:hidden;">
                    <img src="/img/excel.svg" alt="">
                </span>
            </div>
            <div class="accounts-patients_left_table sidebar-invoices-wrap">
                <div class="table-head">
                    <span>Счет №</span>
                    <span>Дата создания</span>
                    <span>Зубы</span>
                    <span>Сумма</span>
                    <span>Тип</span>
                    <span>Статус</span>
                    <span>Дата закрытия</span>
                </div>

                <?php
                $invoices = $model->getFormattedInvoices(['preliminary' => Invoice::NOT_PRELIMINARY]);
                $bills_sum = 0;
                ?>

                <?php if (!empty($invoices)): ?>
                    <?php foreach ($invoices as $invoice): ?>
                        <?php
                        if ($invoice['type'] != Invoice::TYPE_CANCELLED) {
                            $bills_sum += $invoice['total_price'];
                        }
                        ?>
                        <div class="table-body invoice-details-btn" data-id="<?= $invoice['id'] ?>">
                            <div class="body-item">
                                <span>#<?= $invoice['id'] ?></span>
                            </div>
                            <div class="body-item">
                                <?php $invoice_date = explode(' ', $invoice['created_at']); ?>
                                <span><?= date('d.m.Y', strtotime($invoice_date[0])) ?></span>
                                <span><?= date('H:i', strtotime($invoice_date[1])) ?></span>
                            </div>
                            <div class="body-item">
                                <span><?= $invoice['all_teeth'] ?></span>
                            </div>
                            <div class="body-item">
                                <span><?= number_format($invoice['total_price'], 0, '', ' ') ?> сум</span>
                            </div>
                            <div class="body-item">
                                <span class="invoice-type" style="background: <?= Invoice::TYPES_COLOR[$invoice['type']] ?? '' ?>;">
                                    <?= Invoice::TYPES[$invoice['type']] ?? '' ?>
                                </span>
                            </div>
                            <div class="body-item">
                                <?php if ($invoice['type'] != Invoice::TYPE_CANCELLED): ?>
                                    <?php if (($invoice['type'] == Invoice::TYPE_DEBT && $invoice['status'] == Invoice::STATUS_PAID)
                                        || ($invoice['type'] == Invoice::TYPE_INSURANCE && $invoice['status'] == Invoice::STATUS_PAID)
                                        || $invoice['type'] == Invoice::TYPE_CLOSED
                                        || $invoice['type'] == Invoice::TYPE_ENUMERATION && $invoice['status'] == Invoice::STATUS_PAID): ?>
                                        <span style="color: #27A55D">
                                            Оплачен
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #CC3030">
                                            Не оплачен
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="body-item">
                                <?php if (!empty($invoice['closed_at'])): ?>
                                    <?php $invoice_date = explode(' ', $invoice['closed_at']); ?>
                                    <span><?= date('d.m.Y', strtotime($invoice_date[0])) ?></span>
                                    <span><?= date('H:i', strtotime($invoice_date[1])) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="accounts-patients_left_add" style="visibility: hidden;">
                <button type="submit">
                    <img src="/img/plus.svg" alt="">
                </button>
            </div>
        </div>
        <div class="accounts-patients_left_top table-bottom">
            <div class=accounts-patients_left_top_bottom>
                <div class="top_bottom_item">
                    <span>Сумма всех счетов:</span>
                    <span><?= number_format($bills_sum, 0, ' ', ' '); ?> сум</span>
                </div>
                <div class="top_bottom_item">
                    <span>Сумма всех оплаченных счетов:</span>
                    <span><?= number_format($model->getTotalPaidInvoices(), 0, ' ', ' '); ?> сум</span>
                </div>
                <div class="top_bottom_item">
                    <span>Аванс:</span>
                    <span><?= number_format($model->getPrepayment(), 0, ' ', ' '); ?> сум</span>
                </div>
                <div class="top_bottom_item">
                    <span>Долг:</span>
                    <span><?= number_format($model->getDebt(), 0, ' ', ' '); ?> сум</span>
                </div>
            </div>

        </div>
    </div>
    <div class="accounts-patients_right">
        <div class="accounts-patients_right_head">
            <div>
                <p>
                </p>
            </div>
            <div style="visibility: hidden;">
                <img src="/img/excel.svg" alt="">
                <img src="/img/print.svg" alt="">
                <img src="/img/full.svg" alt="">
            </div>
        </div>
        <?= $this->render('_bill-top', [
            'model' => $model,
            'record' => $record,
            'doctors' => $doctors,
            'assistants' => $assistants
        ]) ?>
        <?php if (Yii::$app->user->can('invoice_ajax_create')): ?>
            <div class="accounts-patients_right_checkbox">
                <div class="checkbox_info">
                    <h3>зубы</h3>
                </div>
                <div class="checkbox_info checkbox_info_end">
                    <div class="checkbox_toggle">
                        <input type="checkbox" id="preliminary"/><label for="preliminary">Toggle</label>
                        <span>ПРЕДВАРИТЕЛЬНЫЕ СЧЕТ</span>
                    </div>
                    <div class="checkbox_toggle">
                        <input type="checkbox" id="select_all"/><label for="select_all">Toggle</label>
                        <span>Выбрать все</span>
                    </div>
                    <div class="checkbox_toggle">
                        <input type="checkbox" id="switchs"/><label for="switchs">Toggle</label>
                        <span>Включить детские зубы</span>
                    </div>
                </div>
            </div>
            <?= $this->render('_tooth', []) ?>
            <div class="accounts-patients_right_services">
                <div class="right_services_head">
                    <div class="right_services_head_item">
                        <span>Виды работ</span>
                    </div>
                    <div class="right_services_head_item">
                        <span>Количество</span>
                    </div>
                    <div class="right_services_head_item">
                        <span>Цена</span>
                    </div>
                    <div class="right_services_head_item">
                        <span>Итого</span>
                    </div>
                    <div class="right_services_head_item">
                        <span>Удалить</span>
                    </div>
                </div>
                <div class="right_services_body">
                </div>
                <div class="right_services_body_add">
                    <button type="submit" id="modalBtnpatients" data-path="form-popup">
                        Добавить услугу <img src="/img/plusWhite.svg" alt="">
                    </button>
                </div>
            </div>
            <div class="right_services_body_footer">
                <div class="footer_info">
                    <div class="footer_info_item">
                        <span>
                            Общая сумма (Итого)
                        </span>
                        <span class="footer_info_text_500" id="total_without_discount" data-raw-price="0">
                            0 сум
                        </span>
                    </div>
                    <div class="footer_info_item">
                        <span>
                            Скидка
                        </span>
                        <span id="discount-wrap"
                              data-raw-percent="<?= !empty($model->discount) ? $model->discount : 0 ?>">
                            <?= !empty($model->discount) ? $model->discount : 0 ?>%
                        </span>
                    </div>
                    <div class="footer_info_item">
                        <span class="footer_info_text_500">
                            Общая сумма со скидкой
                        </span>
                        <span class="footer_info_text_800" id="total_with_discount" data-raw-price="0">
                            0 сум
                        </span>
                    </div>
                </div>
                <div class="footer__btn">
                    <div class="footer__btn_right">
                        <button class="btn-reset btn_blue" type="submit" id="save">Сохранить</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->render('_invoice-details-modal', ['model' => $model]) ?>
