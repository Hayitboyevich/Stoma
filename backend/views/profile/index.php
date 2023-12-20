<?php

use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $earning common\models\EmployeeSalary */
/* @var $doctor_schedule_id integer */
/**@var $data ArrayObject */
/**@var $model User */
/**@var $assistants User */
/**@var $earnings User */

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
                    <img src="<?= $model->media ? '/media/download/?id=' . $model->media->id . '.' . $model->media->file_type : '/img/default-avatar.png'; ?>"
                         alt="">
                </div>
                <div class="user__name">
                    <h2><?= $model->getFullName() ?></h2>
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
                                : 0 ?>)
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
                        <?php
                        if (Yii::$app->user->can('doctor')): ?>
                            <div class="user__name_item">
                                <span>Ассистент:</span>
                                <select class="" id="profile-doctor-assistant" style="margin-left: 5px;">
                                    <option value="0">Нет</option>
                                    <?php
                                    if ($assistants !== null): ?>
                                        <?php
                                        foreach ($assistants as $assistant): ?>
                                            <option value="<?= $assistant->id ?>" <?= $assistant->id == $model->assistant_id ? 'selected' : '' ?>><?= $assistant->getFullName(
                                                ) ?></option>
                                        <?php
                                        endforeach; ?>
                                    <?php
                                    endif; ?>
                                </select>
                                <input type="hidden" id="profile-doctor-id" value="<?= $model->id ?>">
                            </div>
                        <?php
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="edit-employee_modals">
        <div class="modal-overlay">
            <div class="modal modal--1" data-target="form-popup_small">

                <div class="modal__title">
                    <h2>Информация о сотруднике</h2>
                    <img id="close__modal" src="img/modalClose.svg" alt="">
                </div>

                <div class="modal__body">
                    <div class="modal__body__inputs">
                        <div class="modal__body-name">
                            <label>
                                ФИО
                                <div class="select_wrapper" id="select6">
                                    <div class="select">
                                        <div class="select__dropdown">
                                            <p>Холматов Улугбек Бахтиярович</p>
                                            <img src="img/modalSelect.svg" alt="">
                                        </div>
                                    </div>
                                    <div class="select_option6">
                                        <div class="select_option_item">
                                            <p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="modal__body-date">
                            <div class="date__wrap">
                                <span class="doctor__date_title">Дата</span>
                                <label class="date">

                                    <div class="select_wrapper" id="select7">
                                        <div class="select">
                                            <div class="select__dropdown">
                                                <div class="select__dropdown_date">
                                                    <img src="img/modalCalendar.svg" alt="">
                                                    <p>12.02.2022</p>
                                                </div>
                                                <img src="img/modal_down.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="select_option7">
                                            <div class="select_option_item">
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                            </div>
                                        </div>
                                    </div>

                                </label>
                            </div>
                            <div class="number__wrap">
                                <label class="input__label" for="phonenumber">
                                    Номер телефона
                                    <input type="text" id="phonenumber" placeholder="+998 91 000 00 01">
                                </label>
                            </div>
                        </div>
                        <div class="modal__body-status">
                            <div class="date__wrap">
                                <span class="doctor__date_title">Дата начала работы</span>
                                <label class="date">

                                    <div class="select_wrapper" id="select8">
                                        <div class="select">
                                            <div class="select__dropdown">
                                                <div class="select__dropdown_date">
                                                    <img src="img/modalCalendar.svg" alt="">
                                                    <p>12.02.2022</p>
                                                </div>
                                                <img src="img/modal_down.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="select_option8">
                                            <div class="select_option_item">
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                            </div>
                                        </div>
                                    </div>

                                </label>
                            </div>
                            <div class="date__wrap">
                                <span class="doctor__date_title">Статус</span>
                                <label class="date">
                                    <div class="select_wrapper" id="select9">
                                        <div class="select">
                                            <div class="select__dropdown">
                                                <div class="select__dropdown_date">
                                                    <p>Работает</p>
                                                </div>
                                                <img src="img/modal_down.svg" alt="">
                                            </div>
                                        </div>
                                        <div class="select_option9">
                                            <div class="select_option_item">
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="modal__body-select">
                            <label>
                                Специализации
                                <div class="select_wrapper" id="select10">
                                    <div class="select">
                                        <div class="select__dropdown">
                                            <div class="select__dropdown_date">
                                                <p>Работает</p>
                                            </div>
                                            <img src="img/modal_down.svg" alt="">
                                        </div>
                                    </div>
                                    <div class="select_option10">
                                        <div class="select_option_item">
                                            <p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            <div class="modal__chip-wrapper">
                                <span class="modal__chip">Стамотолог терапевт<img src="img/chipClose.svg" alt=""></span>
                                <span class="modal__chip">Стамотолог хирург<img src="img/chipClose.svg" alt=""></span>
                                <span class="modal__chip">Стамотолог хирург<img src="img/chipClose.svg" alt=""></span>
                                <span class="modal__chip">Стамотолог хирург<img src="img/chipClose.svg" alt=""></span>
                                <span class="modal__chip">Стамотолог хирург<img src="img/chipClose.svg" alt=""></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal__footer">
                    <button id="close__modal_footer" class="btn__outline" type="submit">отменить</button>
                    <button class="btn__blue " type="submit">Сохранить</button>
                </div>

            </div>
        </div>
    </div>

    <?= $this->render('_salary', [
        'model' => $model,
        'earnings' => $earnings,
        'data' => $data
    ]) ?>
</div>
