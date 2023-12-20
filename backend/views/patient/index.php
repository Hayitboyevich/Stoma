<?php

/** @var array $data */

use yii\helpers\Url;

$this->title = 'Пациенты';

?>
<div class="patients">
    <!-- search -->
    <div class="patients__search">
        <div class="patients__left">
            <div class="patients__filter">
                <img src="/img/filter.svg" alt="">
                <div class="patient-filters-wrap">
                    <div>
                        <input type="checkbox"
                               id="patient_with_discount" <?= $data['discount_patients'] ? 'checked' : ''; ?> />
                        <label for="patient_with_discount">Список пациентов со скидками</label>
                    </div>
                    <button class="sj-btn sj-btn-info apply-patient-filters">применить</button>
                </div>
            </div>
            <div class="patients__input">
                <img src="/img/search_icon.svg" alt="">
                <input type="text" placeholder="Поиск" class="patient-search-input"/>
            </div>
            <button class="btn_top remove-selected-patients">Удалить</button>
            <?php
            if ($data['discount_patients']): ?>
                <div class="active-filter">
                    <label>список пациентов со скидками</label>
                    <span data-name="discount_patients">x</span>
                </div>
            <?php
            endif; ?>
        </div>
        <div class="patients__right">
            <?php
            if (Yii::$app->user->can('patient_ajax_create')): ?>
                <button class="btn_top btn_blue new-patient-btn">
                    Добавить ПАЦИЕНТА<span><img src="/img/icon_plus.svg" alt=""></span>
                </button>
            <?php
            endif; ?>

        </div>
    </div>
    <!--  table -->
    <div class="patients__table" style="height:calc(100vh - 215px); overflow-y: auto;">
        <table cellspacing="0">
            <tr>
                <td></td>
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
                        <span>Дата рождения</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Мобильный номер</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>VIP</span>
                    </div>
                </td>
                <?php
                if (Yii::$app->user->can('view_patient_discount')): ?>
                    <td class="table_head">
                        <div class="filter_text">
                            <a href="<?= Url::to(
                                [
                                    'patient/index',
                                    'discount_patients' => $data['discount_patients'],
                                    'per_page' => $data['per_page'],
                                    'page' => $data['page'],
                                    'sort' => $data['sort'] === 'ASC' ? 'DESC' : 'ASC'
                                ]
                            ) ?>">
                                <span>Скидка</span>
                            </a>
                        </div>
                    </td>
                <?php
                endif; ?>

                <td class="table_head">
                    <div class="filter_text">
                        <span>Лечащий врач</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата последнего визита</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>telegram ID</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($data['patients'] as $patient): ?>
                <tr>
                    <td>
                        <input type="checkbox" class="remove-patient-select" data-id="<?= $patient->id ?>">
                    </td>
                    <td>
                        <p><?= $patient->id; ?></p>
                    </td>
                    <td>
                        <?php
                        $url = Yii::$app->user->identity->role == 'cashier' ? 'finance' : 'update';
                        ?>

                        <a href="/patient/<?= $url; ?>?id=<?= $patient->id; ?>"><?= $patient->lastname; ?> <?= $patient->firstname; ?></a>
                    </td>
                    <td>
                        <?php if (!empty($patient->dob)): ?>
                            <p><?= date('d.m.Y', strtotime($patient->dob)) ?></p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <p><?= $patient->phone ?></p>
                    </td>
                    <td>
                        <?= $patient->vip ? '<p class="tex_green">Да</p>' : '<p class="tex_red">Нет</p>' ?>
                    </td>
                    <?php if (Yii::$app->user->can('view_patient_discount')): ?>
                        <td>
                            <?= !empty($patient->discount)
                                ? '<p class="tex_green">' . $patient->discount . '%</p>'
                                : '<p class="tex_red">0%</p>'; ?>
                        </td>
                    <?php endif; ?>
                    <td>
                        <p>
                            <?= $patient->doctor
                                ? $patient->doctor->getFullName()
                                : '' ?>
                        </p>
                    </td>
                    <td>
                        <?php if (!empty($patient->last_visited)): ?>
                            <p><?= date('d.m.Y H:i', strtotime($patient->last_visited)) ?></p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <p><?= $patient->chat_id ?></p>
                    </td>
                </tr>
            <?php
            endforeach; ?>


        </table>
    </div>
    <!--    pagination -->
    <?= $this->render('_pagination', ['data' => $data]) ?>

</div>
<?= $this->render('_new-patient-modal', []) ?>
