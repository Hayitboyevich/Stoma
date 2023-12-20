<?php

use common\models\constants\UserRole;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
                <div class="user__photo"><img src="/img/userMan.png" alt=""></div>
                <div class="user__name"><h2><?= $model->getFullName() ?></h2></div>
                <div class="user__edit" id="modalBtn_small" data-path="form-popup_small">
                    <span><img src="/img/pen_icon.svg" alt=""> </span>
                    <span><img src="/img/printBig.svg" alt=""> </span>
                </div>
            </div>
            <div class="card user__info">
                <div class="user__info_wrap"><span class="user__info_title">Информация о сотруднике</span>
                    <div class="name user__name">
                        <div class="user__name_item"><span>Роль в системе:</span>
                            <p><?= UserRole::getString($model->role) ?></p></div>
                        <div class="user__name_item"><span>Дата рождения:</span>
                            <p>04.09.1964</p><span>(57лет)</span></div>
                        <div class="user__name_item"><span>Контактный номер:</span>
                            <p class="blue__color"><?= $model->phone ?></p></div>
                    </div>
                </div>
                <div class="user__info_wrap"><span class="user__info_title">Авторизация</span>
                    <div class="balance user__name">
                        <div class="user__name_item"><span>Логин:</span>
                            <p><?= $model->username ?></p>
                        </div>
                    </div>
                </div>
                <div class="user__info_wrap"><span class="user__info_title">информация о работе</span>
                    <div class="balance user__name">
                        <div class="user__name_item"><span>Дата начала работы:</span>
                            <p>13 Января 2020</p></div>
                        <div class="user__name_item"><span>Статус:</span>
                            <p>Работает</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="edit-employee_history">
        <div class="history_tabs">
            <div class="history_text active"><p>Дополнительная информация</p></div>
        </div>
        <div class="edit-employee_history-bottom">
            <div class="edit-employee_history-bottom-left">
                <div class="bottom-title"><p>информация</p></div>
                <div class="bottom-left_info"><span class="text_grey">Email: </span><span class="text_blue"><?= $model->email ?></span>
                </div>
                <div class="bottom-left_info"><span class="text_grey">Пасспорт: </span><span class="text_black">AA 0747205, Выдан 29.01.22013</span>
                </div>
            </div>
            <div class="edit-employee_history-bottom-right">
                <div class="bottom-title"><p>Специализация:</p></div>
                <div class="bottom-tag"><span>Стамотолог терапевт</span></div>
            </div>
        </div>
    </div>
    <div class="edit-employee_modals">
        <div class="modal-overlay">
            <div class="modal modal--1" data-target="form-popup_small">
                <div class="modal__title"><h2>Информация о сотруднике</h2><img id="close__modal"
                                                                               src="/img/modalClose.svg" alt="">
                </div>
                <div class="modal__body">
                    <div class="modal__body__inputs">
                        <div class="modal__body-name"><label>ФИО
                                <div class="select_wrapper" id="select6">
                                    <div class="select">
                                        <div class="select__dropdown"><p>Холматов Улугбек Бахтиярович</p><img
                                                    src="/img/modalSelect.svg" alt=""></div>
                                    </div>
                                    <div class="select_option6">
                                        <div class="select_option_item"><p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p></div>
                                    </div>
                                </div>
                            </label></div>
                        <div class="modal__body-date">
                            <div class="date__wrap"><span class="doctor__date_title">Дата</span> <label
                                        class="date">
                                    <div class="select_wrapper" id="select7">
                                        <div class="select">
                                            <div class="select__dropdown">
                                                <div class="select__dropdown_date"><img src="/img/modalCalendar.svg"
                                                                                        alt="">
                                                    <p>12.02.2022</p></div>
                                                <img src="/img/modal_down.svg" alt=""></div>
                                        </div>
                                        <div class="select_option7">
                                            <div class="select_option_item"><p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p></div>
                                        </div>
                                    </div>
                                </label></div>
                            <div class="number__wrap"><label class="input__label" for="phonenumber">Номер
                                    телефона <input type="text" id="phonenumber"
                                                    placeholder="+998 91 000 00 01"></label></div>
                        </div>
                        <div class="modal__body-status">
                            <div class="date__wrap"><span class="doctor__date_title">Дата начала работы</span>
                                <label class="date">
                                    <div class="select_wrapper" id="select8">
                                        <div class="select">
                                            <div class="select__dropdown">
                                                <div class="select__dropdown_date"><img
                                                            src="/img/modalCalendar.svg" alt="">
                                                    <p>12.02.2022</p></div>
                                                <img src="/img/modal_down.svg" alt=""></div>
                                        </div>
                                        <div class="select_option8">
                                            <div class="select_option_item"><p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p></div>
                                        </div>
                                    </div>
                                </label></div>
                            <div class="date__wrap"><span class="doctor__date_title">Статус</span> <label
                                        class="date">
                                    <div class="select_wrapper" id="select9">
                                        <div class="select">
                                            <div class="select__dropdown">
                                                <div class="select__dropdown_date"><p>Работает</p></div>
                                                <img src="/img/modal_down.svg" alt=""></div>
                                        </div>
                                        <div class="select_option9">
                                            <div class="select_option_item"><p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p>
                                                <p>Недля</p></div>
                                        </div>
                                    </div>
                                </label></div>
                        </div>
                        <div class="modal__body-select"><label>Специализации
                                <div class="select_wrapper" id="select10">
                                    <div class="select">
                                        <div class="select__dropdown">
                                            <div class="select__dropdown_date"><p>Работает</p></div>
                                            <img src="/img/modal_down.svg" alt=""></div>
                                    </div>
                                    <div class="select_option10">
                                        <div class="select_option_item"><p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p>
                                            <p>Недля</p></div>
                                    </div>
                                </div>
                            </label>
                            <div class="modal__chip-wrapper"><span class="modal__chip">Стамотолог терапевт<img
                                            src="/img/chipClose.svg" alt=""></span><span class="modal__chip">Стамотолог хирург<img
                                            src="/img/chipClose.svg" alt=""></span><span class="modal__chip">Стамотолог хирург<img
                                            src="/img/chipClose.svg" alt=""></span><span class="modal__chip">Стамотолог хирург<img
                                            src="/img/chipClose.svg" alt=""></span><span class="modal__chip">Стамотолог хирург<img
                                            src="/img/chipClose.svg" alt=""></span></div>
                        </div>
                    </div>
                </div>
                <div class="modal__footer">
                    <button id="close__modal_footer" class="btn__outline" type="submit">отменить</button>
                    <button class="btn__blue" type="submit">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

</div>
