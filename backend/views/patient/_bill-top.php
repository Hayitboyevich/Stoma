<?php

use common\models\Patient;
use common\models\Reception;
use common\models\User;

/* @var $model Patient; */
/* @var $record Reception */
/* @var $assistants User */
/* @var $doctors User */

?>

<div class="accounts-patients_right_user">
    <div class="accounts-patients_right_user_info">
        <div class="user-left_item">
            <div class="user-left_item_img">
                <img src="<?= isset($model->media)
                    ? '/media/download/?id=' . $model->media->id
                    : '/img/default-avatar.png' ?>"
                    alt="">
            </div>
            <div class="user-left_item_info">
                <span>Пациент</span>
                <h3><?= $model->getFullName() ?></h3>
            </div>
        </div>
    </div>
    <div class="accounts-patients_right_user_info">
        <div class="user-left_item">
            <div class="user-left_item_info">
                <?php if (Yii::$app->user->can('doctor')): ?>
                    <select name="invoice-doctor" style="width: 200px;visibility: hidden;" disabled>
                        <option value="<?= Yii::$app->user->identity->id ?>">
                            <?= Yii::$app->user->identity->getFullName() ?>
                        </option>
                    </select>
                <?php else: ?>
                    <span>Доктор</span>
                    <select name="invoice-doctor" class="select2-instance" style="width: 200px;">
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?= $doctor->id ?>"><?= $doctor->getFullName() ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

            </div>
        </div>
        <div class="user-left_item">
            <div class="user-left_item_info">
                <span>Ассистент</span>
                <select name="invoice-assistant" class="select2-instance" style="width: 200px;">
                    <option selected disabled>Выбрать ассистент</option>
                    <?php foreach ($assistants as $assistant): ?>
                        <option value="<?= $assistant->id ?>" <?= Yii::$app->user->can('doctor') && Yii::$app->user->identity->assistant_id == $assistant->id ? 'selected' : '' ?>>
                            <?= $assistant->getFullName() ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="user-left_item">
            <div class="user-left_item_info">
                <span>Визит</span>
                <select name="reception_id" class="select2-instance" style="width: 300px;">
                    <?php if (!empty($model->visits)): ?>
                        <?php foreach ($model->visits as $visit): ?>
                            <option value="<?= $visit->id ?>" <?= $record && $record->id == $visit->id ? 'selected' : ''; ?>>
                                <?= $visit->record_date ?> с <?= $visit->record_time_from ?> до <?= $visit->record_time_to ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
</div>
