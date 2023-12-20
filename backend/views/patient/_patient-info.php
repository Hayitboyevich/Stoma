<?php

/* @var $model Patient */

/* @var $invoice Invoice */

use common\models\Invoice;
use common\models\Patient;
use yii\helpers\Url;

?>
<div class="edit-patients__wrapper <?= $invoice ? 'edit-patients_wrapper-none' : '' ?>">
    <div class="edit-patients__user">
        <div class="card user__card">
            <div class="user__photo">
                <img src="<?= $model->media ? '/media/download/?id=' . $model->media->id : '/img/default-avatar.png'; ?>"
                     alt="">
                <form action="/patient/change-photo" method="post" enctype="multipart/form-data" class="change-photo">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                           value="<?= Yii::$app->request->csrfToken; ?>"/>
                    <input type="hidden" name="user_id" value="<?= $model->id; ?>"/>
                    <input name="change-photo" type="file" class="change-photo-input" title="редактировать фото"/>
                </form>
            </div>
            <div class="user__name">
                <h2><?= $model->lastname; ?> <?= $model->firstname; ?></h2>
            </div>
            <div class="user__edit">
                <span>
                  <img src="/img/pen_icon.svg" alt="" class="edit-patient">
                </span>
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
                        <p>
                            <?= number_format($model->getPrepayment(), 0, ' ', ' ') ?> Сум
                        </p>
                    </div>
                    <div class="user__name_item">
                        <span>Долг:</span>
                        <p class="red__color">
                            <?= number_format($model->getDebt(), 0, ' ', ' ') ?> Сум
                        </p>
                    </div>
                    <div class="user__name_item">
                        <span>Скидка:</span>
                        <p><?= !empty($model->discount) ? $model->discount : '0'; ?>%</p>
                        <div class="assign_discount">
                            <img src="/img/pen_icon.svg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form class="card user__note" action="<?= Url::to(['patient/update-note', 'id' => $model->id]) ?>" method="post">
        <div class="user__note_top">
            <div class="user__note__title">
                <h2>Особые отметки</h2>
                <img src="/img/pen_icon.svg" alt="">
            </div>
            <div class="user__note__input">
                <input type="text" name="note" class="patient-note-input" placeholder="Напишите особые отметки здесь!"
                       value="<?= $model->note ?>">
                <input type="hidden" class="patient-note-hidden-input" value="<?= $model->note ?>">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                       value="<?= Yii::$app->request->csrfToken ?>"/>
            </div>
        </div>
        <div class="user__note_bottom">
            <div class="user__note__date">
                <span>Последняя редакция</span>
                <span><?= date('d.m.Y', strtotime($model->note_update_at)) ?></span>
            </div>
            <button class="user_note_bottom_button" style="display: <?= $model->note == '' ? 'block' : 'none' ?>"
                    type="submit">Cохранить
            </button>
        </div>
    </form>
</div>
