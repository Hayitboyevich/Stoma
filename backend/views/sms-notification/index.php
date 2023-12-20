<?php

/**@var array $data */

use common\models\SmsNotification;
use yii\helpers\Url;

?>
<div class="patients sms-notification">
    <h3 class="wrapper__box-title">Смс-уведомление</h3>
    <div class="sms-notification-select">
        <div class="statistics_employee-select sms-select-wrapper">
            <form class="statistics_employee-select-left" action="<?= Url::to(['sms-notification/index']) ?>">
                <input type="hidden" name="page" value="<?= $data['page'] ?>"/>
                <input type="hidden" name="per_page" value="<?= $data['per_page'] ?>"/>
                <label class="statistics_employee-select-left-label">
                    с
                    <input type="date" name="start_date" value="<?= $data['start_date'] ?>">
                </label>
                <label class="statistics_employee-select-left-label">
                    по
                    <input type="date" name="end_date" value="<?= $data['end_date'] ?>">
                </label>
                <label class="statistics_employee-select-left-label">
                   Выберите сотрудника
                    <select name="status" id="" class="select">
                        <option value="all">Все</option>
                        <?php if (!empty(SmsNotification::STATUS)): ?>
                            <?php foreach (SmsNotification::STATUS as $key => $status): ?>
                                <option value="<?= $key ?>" <?= $key === $data['status'] ? 'selected' : '' ?>> <?= $status ?> </option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>
                </label>
                <button type="submit" class="btn-reset btn-show">показать</button>
            </form>
        </div>
    </div>
    <div class="employee__table sms-notification__table" style="height:calc(100% - 180px); overflow-y: auto; ">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div class="filter_text">
                        <span>Время отправки</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Номер телефон</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Получател</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Сообщения</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Статус</span>
                    </div>
                </td>
            </tr>
            <?php if (empty($data['sms_notifications'])): ?>
                <tr>
                    <td colspan="5">
                        <div class="filter_text text_center-table">
                            <span style="color: #ff1919">Ничего не найдено</span>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($data['sms_notifications'])): ?>
                <?php foreach ($data['sms_notifications'] as $smsNotification): ?>
                    <tr>
                        <td>
                            <div class="filter_text">
                                <span><?= date('Y-m-d H:i', strtotime($smsNotification->created_at)) ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span><?= $smsNotification->phone ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span><?= $smsNotification->getRecipient() ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span><?= $smsNotification->message ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="filter_text">
                                <span><?= $smsNotification->getStatus() ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>

    <?= $this->render('_pagination', ['data' => $data]) ?>
</div>

