<?php

/* @var $model Patient */
/* @var $invoice Invoice */
/* @var $model Patient */
/* @var $invoice Invoice */
/* @var $patients Patient */

use common\models\Invoice;
use common\models\Patient;
use common\models\Transaction;

$this->title = $model->getFullName();

?>

<div class="edit-patients">
    <div class="edit-patients__top">
        <div class="edit-patients__top_left">
            <ul>
                <li><a href="/patient/index">Пациенты</a></li>
                <li><a href="javascript:void(0)">»</a></li>
                <li class="active_link"><a href=""><?= $model->getFullName() ?></a></li>
            </ul>
        </div>
    </div>

    <div class="edit-patients__wrapper">
        <div class="edit-patients__user">
            <div class="card user__card">
                <div class="user__photo">
                    <img src="<?= $model->media
                        ? '/media/download/?id=' . $model->media->id : '/img/default-avatar.png' ?>"
                         alt="">
                    <form action="/patient/change-photo" method="post" enctype="multipart/form-data"
                          class="change-photo">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                               value="<?= Yii::$app->request->csrfToken ?>"/>
                        <input type="hidden" name="user_id" value="<?= $model->id ?>"/>
                    </form>
                </div>
                <div class="user__name">
                    <h2><?= $model->getFullName() ?></h2>
                </div>
            </div>
            <div class="card user__info">
                <div class="user__info_wrap">
                    <span class="user__info_title">Профиль пользователя</span>
                    <div class="name user__name">
                        <div class="user__name_item">
                            <span>Пол:</span>
                            <p><?= $model::GENDER[$model->gender] ?></p>
                        </div>
                        <div class="user__name_item">
                            <span>Дата рождения:</span>
                            <p><?= $model->dob ?></p>
                        </div>
                        <div class="user__name_item">
                            <span>Мобильный номер:</span>
                            <p class="blue__color"><?= $model->phone ?></p>
                        </div>
                    </div>
                </div>
                <div class="user__info_wrap">
                    <span class="user__info_title">Баланс</span>
                    <div class="balance user__name">
                        <div class="user__name_item">
                            <span>№:</span>
                            <p><?= $model->id ?></p>
                        </div>

                        <div class="user__name_item">
                            <span>Аванс:</span>
                            <p><?= number_format($model->getPrepayment(), 0, ' ', ' ') ?> Сум</p>
                        </div>
                        <div class="user__name_item">
                            <span>Долг:</span>
                            <p class="red__color"><?= number_format($model->getDebt(), 0, ' ', ' ') ?>
                                Сум</p>
                        </div>
                        <div class="user__name_item">
                            <span>Скидка:</span>
                            <p><?= !empty($model->discount) ? $model->discount : '0' ?>%</p>
                        </div>
                    </div>
                </div>
                <div class="user__info_wrap">
                    <div class="user__info_buttons">
                        <button class="add-money-patient btn-reset">Пополнить баланс</button>
                        <button class="transfer-money-patient btn-reset">
                            <svg width="16" height="17" viewBox="0 0 16 17"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.00001 2.09962C7.0885 2.0981 6.18725 2.29196 5.35696 2.66814C5.33081 2.68005 5.30734 2.69712 5.28793 2.71832C5.26853 2.73952 5.2536 2.76441 5.24404 2.79152C5.23448 2.81862 5.23049 2.84737 5.2323 2.87605C5.2341 2.90474 5.24168 2.93276 5.25456 2.95845L5.63594 3.72212C5.66063 3.77149 5.70347 3.80938 5.75547 3.82787C5.80747 3.84636 5.86462 3.84401 5.91492 3.82131C6.57111 3.52897 7.28167 3.37865 8.00001 3.3802C8.92959 3.37983 9.8417 3.63278 10.6383 4.11187C11.435 4.59096 12.0861 5.27808 12.5216 6.09941C12.9572 6.92074 13.1608 7.84524 13.1105 8.77358C13.0602 9.70193 12.758 10.599 12.2363 11.3685L11.5639 10.696C11.534 10.6662 11.496 10.6459 11.4546 10.6377C11.4132 10.6296 11.3704 10.6338 11.3314 10.6499C11.2924 10.666 11.2591 10.6933 11.2356 10.7284C11.2121 10.7634 11.1995 10.8046 11.1994 10.8468V13.1934C11.1994 13.312 11.2946 13.4079 11.4123 13.4079H13.7586C13.8009 13.408 13.8423 13.3954 13.8775 13.3719C13.9127 13.3484 13.9401 13.3149 13.9563 13.2758C13.9724 13.2366 13.9766 13.1936 13.9683 13.1521C13.9599 13.1106 13.9394 13.0725 13.9094 13.0426L13.1539 12.2862C13.9634 11.1899 14.4 9.86292 14.3996 8.5001C14.3983 6.80306 13.7236 5.1759 12.5237 3.97598C11.3238 2.77606 9.69682 2.10069 8.00001 2.09962ZM2.23901 3.59229C2.19677 3.59225 2.15546 3.60473 2.12032 3.62816C2.08517 3.65159 2.05775 3.68491 2.04153 3.72392C2.02531 3.76292 2.02101 3.80586 2.02918 3.84731C2.03735 3.88875 2.05762 3.92685 2.08742 3.95678L2.84454 4.7132C2.0352 5.8098 1.59885 7.13711 1.59961 8.5001C1.60045 9.57029 1.86941 10.6232 2.38193 11.5627C2.89444 12.5021 3.63417 13.2981 4.53352 13.878C5.43287 14.4579 6.46317 14.8031 7.53028 14.8821C8.5974 14.9612 9.66731 14.7715 10.6423 14.3305C10.6683 14.3185 10.6917 14.3015 10.711 14.2804C10.7303 14.2592 10.7452 14.2344 10.7548 14.2074C10.7643 14.1804 10.7684 14.1517 10.7666 14.1232C10.7649 14.0946 10.7574 14.0666 10.7447 14.041L10.3617 13.2773C10.337 13.2285 10.2946 13.1911 10.2431 13.1726C10.1917 13.1542 10.1351 13.1561 10.0851 13.1781C9.42903 13.471 8.71847 13.6218 8.00001 13.6208C7.06997 13.6218 6.15725 13.3692 5.36001 12.8903C4.56276 12.4113 3.91115 11.724 3.47524 10.9023C3.03934 10.0806 2.83563 9.15565 2.88604 8.22685C2.93644 7.29805 3.23904 6.40056 3.7613 5.63089L4.43617 6.30425C4.46613 6.33346 4.504 6.35324 4.5451 6.36112C4.58619 6.36901 4.6287 6.36465 4.66734 6.34859C4.70598 6.33253 4.73906 6.30548 4.76246 6.27079C4.78587 6.2361 4.79858 6.1953 4.79901 6.15345V3.80679C4.79911 3.77866 4.79366 3.75078 4.78297 3.72476C4.77228 3.69874 4.75656 3.67509 4.73671 3.65516C4.71686 3.63523 4.69327 3.61942 4.66729 3.60863C4.64131 3.59784 4.61346 3.59229 4.58534 3.59229H2.23901ZM7.99921 5.08576C6.1165 5.08576 4.58695 6.61794 4.58695 8.50091C4.58695 10.3839 6.1165 11.9136 7.99921 11.9136C9.88191 11.9136 11.4139 10.3839 11.4139 8.50091C11.4139 6.61794 9.88191 5.08576 7.99921 5.08576ZM7.99921 5.93814C8.10483 5.93814 8.21207 6.0091 8.21207 6.15183V6.60342C8.45275 6.65306 8.66902 6.78405 8.82452 6.97437C8.98001 7.1647 9.06526 7.40275 9.06594 7.64853C9.07239 7.93884 8.63215 7.93884 8.6386 7.64853C8.6386 7.29291 8.35559 7.00905 7.99921 7.00905C7.64282 7.00905 7.35981 7.29291 7.35981 7.64853C7.35981 8.00416 7.64363 8.28802 7.99921 8.28802C8.58619 8.28802 9.06594 8.76945 9.06594 9.35571C9.06594 9.8702 8.69746 10.3016 8.21126 10.4008V10.8492C8.21126 11.1338 7.78554 11.1338 7.78554 10.8492V10.4008C7.54515 10.3509 7.32926 10.2197 7.17408 10.0294C7.0189 9.83914 6.93388 9.60126 6.93328 9.35571C6.93973 9.07749 7.35336 9.07749 7.36062 9.35571C7.36062 9.71214 7.64282 9.99519 7.99921 9.99519C8.35559 9.99519 8.6386 9.71214 8.6386 9.35571C8.6386 8.99927 8.35559 8.71461 7.99921 8.71461C7.71677 8.71376 7.44614 8.60117 7.24642 8.40142C7.04671 8.20168 6.93413 7.93101 6.93328 7.64853C6.93328 7.13485 7.30015 6.70423 7.78554 6.60423V6.15183C7.78554 6.0091 7.89277 5.93814 7.99921 5.93814Z"
                                      />
                            </svg>
                            Перевод денег
                        </button>
                        <button class="withdraw-money-patient btn-reset">Снять деньги</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="edit-patients__history">
        <div id="accountPay" class="accounts-patients_price price__active">
            <?= $this->render('_add-money-modal', ['model' => $model]) ?>

            <?= $this->render('_withdraw-money-modal', ['model' => $model]) ?>

            <div class="accounts-patients_left" style="width: 50%">
                <div class="accounts-patients_left_top table-top">
                    <div class="accounts-patients_left_head">
                        <p>
                            Счета
                        </p>
                        <span>
                            <img src="img/excel.svg" alt="">
                        </span>
                    </div>
                    <div class="accounts-patients_left_table">
                        <div class="table-head">
                            <span>№</span>
                            <span>Дата</span>
                            <span>Зубы</span>
                            <span>Сумма</span>
                            <span>Тип</span>
                            <span>Статус</span>
                        </div>
                        <?php
                        $invoices = $model->getFormattedInvoices(['preliminary' => Invoice::NOT_PRELIMINARY]);
                        $totalPrice = 0;
                        $paidInvoiceTotal = 0
                        ?>
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $this_invoice): $totalPrice += $this_invoice['total_price']; ?>
                                <?php
                                if ($this_invoice['type'] === Invoice::TYPE_CLOSED) {
                                    $paidInvoiceTotal += $this_invoice['total_price'];
                                }
                                ?>
                                <div class="table-body invoice-details-btn" data-id="<?= $this_invoice['id'] ?>"
                                     style="cursor: pointer">
                                    <div class="body-item">
                                        <span>#<?= $this_invoice['id'] ?></span>
                                    </div>
                                    <div class="body-item">
                                        <?php $invoice_date = explode(' ', $this_invoice['created_at']); ?>
                                        <span><?= date('d.m.Y', strtotime($invoice_date[0])) ?></span>
                                        <span><?= date('H:i', strtotime($invoice_date[1])) ?></span>
                                    </div>
                                    <div class="body-item">
                                        <span><?= $this_invoice['all_teeth'] ?></span>
                                    </div>
                                    <div class="body-item">
                                        <span><?= number_format($this_invoice['total_price'], 0, ' ', ' ') ?> сум</span>
                                    </div>
                                    <div class="body-item">
                                        <span class="type_<?= $this_invoice['type'] ?>">
                                            <?= Invoice::TYPES[$this_invoice['type']] ?>
                                        </span>
                                    </div>
                                    <div class="body-item">
                                        <?php if ($this_invoice['type'] != Invoice::TYPE_CANCELLED): ?>
                                            <?php if (($this_invoice['type'] == Invoice::TYPE_DEBT && $this_invoice['status'] == Invoice::STATUS_PAID) || ($this_invoice['type'] == Invoice::TYPE_INSURANCE && $this_invoice['status'] == Invoice::STATUS_PAID) || ($this_invoice['type'] == Invoice::TYPE_ENUMERATION && $this_invoice['status'] == Invoice::STATUS_PAID) || $this_invoice['type'] == Invoice::TYPE_CLOSED): ?>
                                                <span class="status_paid">
                                                    Оплачен
                                                </span>
                                            <?php else: ?>
                                                <span class="status_debt">
                                                    Не оплачен
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="accounts-patients_left_bottom">
                        <div class="accounts-patients_left_bottom_item">
                            <span class="text__bold">
                                Сумма всех счетов:
                            </span>
                            <span class="text__bold">
                                <?= number_format($totalPrice, 0, ' ', ' ') ?> сум
                            </span>
                        </div>

                        <div class="accounts-patients_left_bottom_item">
                            <span class="text_small">
                                Сумма всех оплаченных счетов:
                            </span>
                            <span class="text_green">
                                <?= number_format($paidInvoiceTotal, 0, ' ', ' ') ?> сум
                            </span>
                        </div>
                    </div>
                </div>

                <div class="accounts-patients_left_top table-top">
                    <div class="accounts-patients_left_head">
                        <p>
                            Предварительные счета
                        </p>
                        <span>
                            <img src="img/excel.svg" alt="">
                        </span>
                    </div>
                    <div class="accounts-patients_left_table">
                        <div class="table-head">
                            <span>№</span>
                            <span>Дата</span>
                            <span>Зубы</span>
                            <span>Сумма</span>
                            <span>Тип</span>
                        </div>
                        <?php
                        $invoices = $model->getFormattedInvoices(['preliminary' => Invoice::PRELIMINARY]);
                        $totalPrice = 0;
                        $paidInvoiceTotal = 0
                        ?>
                        <?php if (!empty($invoices)): ?>
                            <?php foreach ($invoices as $this_invoice): $totalPrice += $this_invoice['total_price']; ?>
                                <?php
                                if ($this_invoice['type'] === Invoice::TYPE_CLOSED) {
                                    $paidInvoiceTotal += $this_invoice['total_price'];
                                }
                                ?>
                                <div class="table-body invoice-details-btn" data-id="<?= $this_invoice['id'] ?>"
                                     style="cursor: pointer">
                                    <div class="body-item">
                                        <span>#<?= $this_invoice['id'] ?></span>
                                    </div>
                                    <div class="body-item">
                                        <?php $invoice_date = explode(' ', $this_invoice['created_at']); ?>
                                        <span><?= date('d.m.Y', strtotime($invoice_date[0])) ?></span>
                                        <span><?= date('H:i', strtotime($invoice_date[1])) ?></span>
                                    </div>
                                    <div class="body-item">
                                        <span><?= $this_invoice['all_teeth'] ?></span>
                                    </div>
                                    <div class="body-item">
                                        <span><?= number_format($this_invoice['total_price'], 0, ' ', ' ') ?> сум</span>
                                    </div>
                                    <div class="body-item">
                                        <span class="type_2">
                                            Предварительный
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="accounts-patients_left_bottom">
                        <div class="accounts-patients_left_bottom_item">
                            <span class="text__bold">
                                Сумма всех счетов:
                            </span>
                            <span class="text__bold">
                                <?= number_format($totalPrice, 0, ' ', ' ') ?> сум
                            </span>
                        </div>

                        <div class="accounts-patients_left_bottom_item">
                            <span class="text_small">
                                Сумма всех оплаченных счетов:
                            </span>
                            <span class="text_green">
                                <?= number_format($paidInvoiceTotal, 0, ' ', ' ') ?> сум
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accounts-patients_right" style="width: 50%;">
                <div class="accounts-patients_left_top table-top">
                    <div class="accounts-patients_left_head">
                        <p>
                            История транзакций
                        </p>
                        <span>
                            <img src="img/excel.svg" alt="">
                        </span>
                    </div>
                    <div class="accounts-patients_left_table">
                        <div class="table-head">
                            <span>Дата</span>
                            <span>Приход / Расход</span>
                            <span>Тип</span>
                            <span>Тип оплаты</span>
                            <span>Счет №</span>
                            <span>Создал</span>
                        </div>
                        <?php if (!empty($model->transactions)): ?>
                            <?php foreach ($model->transactions as $transaction): ?>
                                <div class="table-body">
                                    <div class="body-item">
                                        <?php $transaction_date = explode(' ', $transaction->created_at); ?>
                                        <span><?= date('d.m.Y', strtotime($transaction_date[0])) ?></span>
                                        <span><?= date('H:i', strtotime($transaction_date[1])) ?></span>
                                    </div>
                                    <div class="body-item">
                                        <?php if ($transaction->type === Transaction::TYPE_PAY || $transaction->type === Transaction::TYPE_WITHDRAW_MONEY): ?>
                                            <span class="tex_red">
                                                - <?= number_format($transaction->amount, 0, ' ', ' ') ?> сум
                                            </span>
                                        <?php else: ?>
                                            <span class="tex_green">
                                                + <?= number_format($transaction->amount, 0, ' ', ' ') ?> сум
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="body-item">
                                        <?php if ($transaction->is_transfer): ?>
                                            <span>Перевод денег</span>
                                        <?php else: ?>
                                            <span><?= Transaction::TYPE[$transaction->type] ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="body-item">
                                        <span>
                                            <?= Transaction::PAYMENT_METHODS[$transaction->payment_method] ?? '-' ?>
                                        </span>
                                    </div>
                                    <div class="body-item">
                                        <span>
                                            <?= !empty($transaction->invoice_id) ? "#{$transaction->invoice_id}" : "" ?>
                                        </span>
                                    </div>
                                    <div class="body-item">
                                        <span><?= $transaction->user->getFullName() ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->render('_transfer-money-modal', [
    'model' => $model,
    'patients' => $patients
]) ?>
<?= $this->render('_invoice-details-modal', ['model' => $model]) ?>
<?= $this->render('_invoice-warning-modal', ['model' => $model]) ?>
<?= $this->render('_invoice-pay') ?>