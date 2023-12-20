<?php

use common\models\EmployeeSalary;
use common\models\User;
use yii\helpers\Url;

/**@var $data array */
/**@var $employee User */
/**@var $users User */
/**@var $earning EmployeeSalary */

?>
<div class="salary">
    <div class="salary__wrapper-card">
        <div class="salary__wrapper-card-item">
            <div class="card__item-head">
                <div class="card__item-left">
                    <p>ЗАРПЛАТА</p>
                </div>
                <div class="card__item-right"></div>
            </div>

            <div class="card__item-body">
                <div class="card__item-body-item">
                    <div class="item__title">
                        <span>Сотрудник</span>
                    </div>
                    <div class="item__title">
                        <span>Должность</span>
                    </div>
                    <div class="item__title">
                        <span>Кол-во пациентов</span>
                    </div>
                    <div class="item__title">
                        <span>Зарплата</span>
                    </div>
                </div>

                <?php
                $footerTotal = 0;
                foreach ($users as $employee): ?>
                    <?php

                    $total = User::getTotalEarnings($employee->id, $data['start_date'], $data['end_date']);
                    $footerTotal += $total;

                    ?>
                    <a target="_blank" href="<?= Url::to(
                        [
                            'site/view-earnings',
                            'userId' => $employee->id,
                            'startDate' => $data['start_date'],
                            'endDate' => $data['end_date']
                        ]
                    ) ?>"
                       class="card__item-body-item">
                        <div class="item">
                            <span><?= $employee->getFullName() ?></span>
                        </div>
                        <div class="item">
                            <span><?= User::USER_ROLE[$employee->role] ?></span>
                        </div>
                        <div class="item">
                            <span>
                                <?= User::getCountPatients($employee->id, $data['start_date'], $data['end_date']) ?>
                            </span>
                        </div>
                        <div class="item">
                            <span><?= number_format($total ?? 0, 0, ' ', ' ') ?> сум</span>
                        </div>
                    </a>
                <?php
                endforeach; ?>

                <div class="card__item-body-item bg__blue">
                    <div class="item">
                        <span>Итого:</span>
                    </div>
                    <div class="item"></div>
                    <div class="item"></div>
                    <div class="item">
                        <span><?= number_format($footerTotal, 0, ' ', ' ') ?> сум</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
