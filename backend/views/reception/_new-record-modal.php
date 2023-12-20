<?php

use common\models\PriceList;
use common\models\User;

/**@var $doctors User */
/**@var $priceLists PriceList */
/**@var $timeFrom string */
/**@var $timeTo string */
/**@var $maxDob string */
?>

<div class="modals reception-new-patient">
    <div class="modal-overlay">
        <div style="background: white" class="modal--1" data-target="form-popup">
            <div class="modal__title">
                <h2>Записать пациента</h2>
                <img id="close__modal" src="/img/modalClose.svg" alt="">
            </div>
            <div class="modal__patients">
                <label class="label4 input__label new-patient-fields" for="phonenumber">
                    <input type="text" id="phonenumber" placeholder="Номер телефона 998903334455" name="phone"/>
                </label>
            </div>
            <div class="modal__patients">
                <input type="hidden" name="record_id" value=""/>
                <input type="hidden" name="patient_id" id="patient_id">
                <label class="label2 input__label new-patient-fields" for="lastname">
                    <input type="text" id="lastname" placeholder="Фамилия" name="last_name" minlength="2" maxlength="20"/>
                </label>
                <label class="label3 input__label new-patient-fields" for="name">
                    <input type="text" id="name" placeholder="Имя" name="first_name" minlength="2" maxlength="20"/>
                </label>
            </div>

            <div class="modal__doctor new-patient-fields" style="padding-top: 0 !important;">
                <div class="modal__doctor_date">
                    <div class="doctor__date">
                        <span class="doctor__date_title">Дата рождения</span>
                        <label class="date" for="">
                            <div class="select_wrapper" id="new-birthday">
                                <div class="select">
                                    <input id="new-birthday-date" type="date" style="width: 100%;border: none;" name="birthday" max="<?= $maxDob ?>" />
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="doctor__date">
                        <span class="doctor__date_title">Пол</span>
                        <div class="doctor__date__time">
                            <label class="time new_gender" for="" style="width: 253px">
                                <select name="gender" id="gender">
                                    <option value="M" selected>
                                        Муж.
                                    </option>
                                    <option value="F">
                                        Жен.
                                    </option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal__doctor">
                <label class="label_select" style="width: 253px; margin-right: 10px;display: inline-block;">
                    Выберите Врача
                    <div class="select_wrapper" id="select-doctor-wrap">
                        <select class="select2-instance" name="doctor_id">
                            <option></option>
                            <?php if (!empty($doctors)): ?>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?= $doctor->id ?>"><?= $doctor->lastname; ?> <?= $doctor->firstname; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </label>
                <label class="label_select" style="width: 253px;display: inline-block">
                    Выберите направление
                    <div class="select_wrapper" id="select4">
                        <select class="select2-instance" name="category_id">
                            <option></option>
                            <?php if (!empty($priceLists)): ?>
                                <?php foreach ($priceLists as $priceList): ?>
                                    <option value="<?= $priceList->id; ?>"><?= $priceList->section; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </label>
                <div class="modal__doctor_date">

                    <div class="doctor__date">
                        <span class="doctor__date_title">Дата</span>
                        <label class="date" for="">
                            <div class="select_wrapper" id="select5">
                                <div class="select">
                                    <input id="new-record-date" type="date" style="width: 100%;border: none;" name="date"/>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="doctor__date">
                        <span class="doctor__date_title">Время</span>
                        <div class="doctor__date__time">
                            <label class="time time_from_wrap" for="">
                                <input type="time" id="time_from" name="time_from" min="<?= date("H:i", strtotime($timeFrom)) ?>" max="<?= date("H:i", strtotime($timeTo)) ?>">
                            </label>
                            <label class="time time_to_wrap" for="">
                                <span class="doctor__date_title record-duration">Длительность</span>
                                <select name="time_to" id="time_to">
                                    <option value="15">15 мин.</option>
                                    <option value="30">30 мин.</option>
                                    <option value="45">45 мин.</option>
                                    <option value="60">1 час</option>
                                    <option value="75">1 час 15 мин.</option>
                                    <option value="90">1 час 30 мин.</option>
                                    <option value="105">1 час 45 мин.</option>
                                    <option value="120">2 часа</option>
                                    <option value="150">2 часа 30 мин.</option>
                                    <option value="180">3 часа</option>
                                    <option value="240">4 часа</option>
                                    <option value="300">5 часа</option>
                                    <option value="360">6 часа</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal__doctor_comment">
                    <label for="">
                        <textarea name="comments" id="" placeholder="Комментарии"></textarea>
                    </label>
                </div>

            </div>
            <div class="modal__checkbox">
                <div class="modal__checkbox_toggle">
                    <span>SMS Уведомление </span>
                    <input type="checkbox" id="switch" name="sms"/>
                    <label for="switch" class="sms-switch sms-off">Toggle</label>
                </div>
                <div class="modal__checkbox_btn sms-time-block sms-hide-block">
                    <button name="sms-time" class="checkbox__btn_orange sms-time-select" type="submit"
                            value="day_before">За день
                    </button>
                    <button class="checkbox__btn_outline sms-time-select" type="submit" value="on_the_day">В день
                        приёма
                    </button>
                </div>
            </div>
            <div class="modal__footer">
                <button id="close__modal_footer" type="submit" class="btn__outline">
                    Отменить
                </button>
                <button type="submit" class="btn__blue add-new-record">Записать</button>
            </div>
        </div>
    </div>
</div>
