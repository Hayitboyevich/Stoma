<?php

use common\models\constants\UserRole;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $doctor_schedule_id integer */
/* @var $assistants User */


$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edit-employee">
    <div class="edit-employee__top">
        <div class="edit-employee__top_left">
            <ul>
                <li><a href="">Сотрудники</a></li>
                <li><a href="javascript:void(0)">»</a></li>
                <li class="active_link"><a href=""><?= $model->getFullName() ?></a></li>
            </ul>
        </div>
    </div>
    <div class="edit-employee_wrapper">
        <div class="edit-employee__user">
            <div class="card user__card">
                <div class="user__photo">
                    <img src="<?= $model->media ? '/media/download/?id=' . $model->media->id : '/img/default-avatar.png'; ?>"
                         alt="">
                    <form action="/user/change-photo" method="post" enctype="multipart/form-data" class="change-photo">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>"
                               value="<?= Yii::$app->request->csrfToken; ?>"/>
                        <input type="hidden" name="user_id" value="<?= $model->id ?>"/>
                        <input name="change-photo" type="file" class="change-photo-input" title="редактировать фото"/>
                    </form>
                </div>
                <div class="user__name">
                    <h2><?= $model->getFullName() ?></h2>
                </div>
                <div class="user__edit">
          <span id="update-user-btn" data-path="form-popup_small">
            <img src="/img/pen_icon.svg" alt="">
          </span>
                    <span>
        </span>
                </div>
            </div>
            <div class="card user__info">
                <div class="user__info_wrap">
                    <span class="user__info_title">Информация о сотруднике</span>
                    <div class="name user__name">
                        <div class="user__name_item">
                            <span>Роль в системе:</span>
                            <p><?= User::USER_ROLE[$model->role] ?></p>
                        </div>
                        <div class="user__name_item">
                            <span>Дата рождения:</span>
                            <p><?= $model->dob ?></p>
                            <span>
                                (возраст: <?= isset($model->dob)
                                    ? date_diff(date_create($model->dob), date_create('now'))->y
                                : 0?> )
                            </span>
                        </div>
                        <div class="user__name_item">
                            <span>Контактный номер:</span>
                            <p class="blue__color"><?= $model->phone ?></p>
                        </div>
                    </div>
                </div>
                <div class="user__info_wrap">
                    <span class="user__info_title">Авторизация</span>
                    <div class="balance user__name">
                        <div class="user__name_item">
                            <span>Логин:</span>
                            <p><?= $model->username ?></p>
                        </div>

                    </div>
                </div>
                <div class="user__info_wrap">
                    <span class="user__info_title">информация о работе</span>
                    <div class="balance user__name">
                        <div class="user__name_item">
                            <span>Дата начала работы:</span>
                            <p><?= $model->work_start_date ?></p>
                        </div>
                        <div class="user__name_item">
                            <span>Статус:</span>
                            <p><?= User::WORK_STATUS[$model->work_status] ?></p>
                        </div>
                        <?php if ($model->role === UserRole::ROLE_DOCTOR): ?>
                            <div class="user__name_item">
                                <span>Ассистент:</span>
                                <select id="profile-doctor-assistant" style="margin-left: 5px;">
                                    <option value="0">Нет</option>
                                    <?php if ($assistants !== null): ?>
                                        <?php foreach ($assistants as $assistant): ?>
                                            <option value="<?= $assistant->id ?>" <?= $assistant->id == $model->assistant_id ? 'selected' : '' ?>>
                                                <?= $assistant->getFullName() ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <input type="hidden" id="profile-doctor-id" value="<?= $model->id ?>">
                            </div>

                            <div class="user__name_item">
                                <span>Изменить расписание:</span>
                                <p>
                                    <a href="<?= Url::to(['doctor-schedule/index', 'id' => $model->id]) ?>">
                                        <img src="/img/calendar.svg" alt="">
                                    </a>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="edit-employee_history">
        <div class="history_tabs">
            <div class="history_text active">
                <p>Дополнительная информация</p>
            </div>
        </div>
        <div class="edit-employee_history-bottom">
            <div class="edit-employee_history-bottom-left">
                <div class="bottom-title">
                    <p>
                        информация
                    </p>
                </div>
                <div class="bottom-left_info">
          <span class="text_grey">
            Email:
          </span>
                    <span class="text_blue">
            <?= $model->email ?>
          </span>
                </div>

            </div>
            <div class="edit-employee_history-bottom-right">
                <div class="bottom-title">
                    <p>
                        Специализация:
                    </p>
                </div>
                <div class="bottom-tag">
                    <?php if (!empty($model->categories)): ?>
                        <?php foreach ($model->categories as $category): ?>
                            <span><?= $category->section ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->render('_percent', ['model' => $model]) ?>
    <?= $this->render('_permissions', ['model' => $model]) ?>
    <?= $this->render('_new-doctor-modal', ['model' => $model]) ?>
</div>
