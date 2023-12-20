<?php

/**@var $model Patient*/

use common\models\Patient;

?>

<div class="withdraw-money-modal">
    <div class="withdraw-money-form-wrap">
        <div class="accounts-patients_center withdraw-money-container">
            <h5 class="withdraw-money-container-title">Снять деньги со счета пациента</h5>
            <input type="hidden" id="patient-id" value="<?= $model->id ?>" />
            <div class="accounts-patients_right_pay" style="border-top: 1px solid #dcdee4;">
                <div class="accounts-patients_right_pay_right" style="width: 100%">
                    <div class="pay_right">
                        <div class="pay_right_input">
                            <input type="number" name="amount" placeholder="Введите сумму">
                            <div class="select_wrapper" id="select27">
                                <div class="select">
                                    <div class="select__dropdown">
                                        <p>UZS</p>
                                    </div>
                                    <img src="img/selectIcon.svg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pay_right" style="margin-top: 10px;">
                        <textarea style="width: 100%; padding: 10px; font-size: 14px;" id="reason" name="reason" rows="4" placeholder="Введите причину" cols="50"></textarea>
                    </div>
                    <div class="pay_right__btn">
                        <button type="button" class="btn-reset withdraw-money-money-btn">ввести</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
