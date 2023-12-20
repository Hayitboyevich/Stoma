<?php

use common\models\Config;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $configs Config */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-table">
    <div class="setting-table-header">
        <h2 class="setting-table-title">Настройки</h2>
<!--        <a class="section-btn">Добавить <img src="/img/icon_plus.svg" alt=""></a>-->
    </div>

    <div class="setting-table__table" style="height:calc(100% - 35px); overflow-y: auto;">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Ключ</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Значение</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Действие</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($configs as $config): ?>
                <tr class="tr">
                    <td class="table__body-td">
                        <p>
                            <?= $config->key ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $config->value ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <a href="<?= Url::to(['config/update', 'key' => $config->key]) ?>">
                            <img src="../img/pen_icon.svg" alt="">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>
</div>
