<?php

use common\models\User;
use yii\helpers\Url;

/* @var $users User */

$this->title = 'Сотрудники';

?>
<div class="employee">
    <!-- search -->
    <div class="employee__search">
        <div class="employee__left">
            <button class="btn_top remove-selected-users">Удалить</button>
        </div>
        <div class="employee__right">
            <button class="btn_top btn_blue new-doctor-btn">
                Добавить<span class="employee__right-mobile">учетную запись</span><span><img src="/img/icon_plus.svg" alt=""></span>
            </button>
        </div>
    </div>
    <!--  table -->
    <div class="employee__table" style="height:calc(100% - 60px); overflow-y: auto">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <input type="checkbox">
                </td>
                <td>
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>ФИО</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Роль</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Мобильный номер</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Логин</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Статус</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата рождения</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата начала работы</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Телеграм ID</span>
                    </div>
                </td>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <input type="checkbox" class="remove-user-select" data-id="<?= $user->id ?>"/>
                    </td>
                    <td>
                        <p><?= $user->id ?></p>
                    </td>
                    <td>
                        <a href="<?= Url::to(['user/update', 'id' => $user->id]
                        ) ?>"><?= $user->lastname ?> <?= $user->firstname ?></a>
                    </td>
                    <td>
                        <p><?= User::USER_ROLE[$user->role] ?? '-' ?></p>
                    </td>
                    <td>
                        <p><?= $user->phone ?></p>
                    </td>
                    <td>
                        <p><?= $user->username ?></p>
                    </td>
                    <td>
                        <p class="<?= $user->work_status == User::WORK_STATUS_AVAILABLE ? 'tex_green' : 'tex_red'; ?>">
                            <?= User::WORK_STATUS[$user->work_status] ?>
                        </p>
                    </td>
                    <td>
                        <p><?= $user->dob ?></p>
                    </td>
                    <td>
                        <p><?= $user->work_start_date ?></p>
                    </td>
                    <td>
                        <p><?= $user->chat_id ?></p>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?= $this->render('_new-doctor-modal') ?>

