<?php

/** @var $model Patient */

use common\models\Config;
use common\models\Patient;

?>

<div class="assign-discount-modal">
    <div class="discount-form-wrap">
        <div class="modal__sale">
            <div class="modal__sale-header director-wrap">
                <h3 class="modal__sale-title">Назначить скидку пациенту</h3>
                <img src="/img/scheduleNew/IconClose.svg" onclick="closeModal('.assign-discount-modal')" alt="">
            </div>
            <div class="modal__sale-header others-wrap">
                <h3 class="modal__sale-title">Отправить заявку на скидку</h3>
                <img src="/img/scheduleNew/IconClose.svg" onclick="closeModal('.assign-discount-modal')" alt="">
            </div>
            <?php $discounts = Config::findOne('discounts'); ?>
            <?php $options = explode(',', $discounts->value); ?>
            <div class="modal__sale-body">
                <p class="modal__sale-body-text">Размер скидки:</p>
                <div class="modal__sale-body-boxes">
                    <div class="box sale_box" data-discount="0">0</div>
                    <?php foreach ($options as $option): ?>
                        <div data-discount="<?= $option ?>" class="box sale_box"><?= $option ?>%</div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal__sale-footer director-wrap">
                <button data-patient-id="<?= $model->id ?>" type="button"
                        class="btn-reset btn-blue assign-discount-btn">
                    Назначить
                </button>
            </div>
            <div class="modal__sale-footer others-wrap">
                <button data-patient-id="<?= $model->id ?>" type="button"
                        class="btn-reset btn-blue request-discount-btn">
                    Отправить
                </button>
            </div>
        </div>
    </div>
</div>
