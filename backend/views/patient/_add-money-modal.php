<?php

/**@var $model Patient*/

use common\models\Patient;
use common\models\Transaction;

?>

<div class="add-money-modal">
    <div class="add-money-form-wrap">
        <div class="accounts-patients_center" style="width: 100%;">
            <h5 class="accounts-patients_center-title">Внесение денег на счет пациента</h5>
            <input type="hidden" id="patient-id" value="<?= $model->id ?>" />
            <div class="accounts-patients_right_pay" style="border: none">
                <div class="accounts-patients_right_pay_right" style="width: 100%">
                    <div class="pay_right">
                        <div class="pay_right_input">
                            <input type="number" name="amount" placeholder="Введите сумму">
                            <div class="select_wrapper" id="select27">
                                <div class="select">
                                    <div class="select__dropdown">
                                        <p>UZS</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pay_right_type">
                        <?php foreach(Transaction::PAYMENT_METHODS as $key => $value): ?>
                            <span class="<?= $key == 'cash' ? 'active' : '' ?>" data-payment-method="<?= $key; ?>"><?= $value ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="pay_right">
                    <label class="checkDollarWrapper" >
                    <input type="checkbox" name="dollar" id="checkDollar" class="checkDollar" onclick='handleClick(this)'>
                        В долларах
                    </label>
                        <div id="dollarInput" class="pay_right_input">
                            <input type="number" name="amount" placeholder="Введите курс доллара">
                            <div class="select_wrapper" id="select27">
                                <div class="select">
                                    <div class="select__dropdown">
                                        <p>USD</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pay_right__btn">
                        <button type="button" class="btn-reset add-money-btn">ввести</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
