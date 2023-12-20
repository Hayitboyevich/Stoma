<?php

/** @var array $data */

/** @var PriceList $priceLists */

/** @var TechnicianPriceList $technicianPriceLists */

use common\models\PriceList;
use common\models\TechnicianPriceList;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Услуги';

?>

<div class="patients">
    <div class="patients__search">

        <div class="patients__left">
            <a href="<?= Url::to(['price-list/index']) ?>">
                <button class='patients__left-button'>
                    <img src="/img/IconBack.svg" alt="IconBack">
                </button>
            </a>
            <p>
                Услуги
            </p>
        </div>
        <div class="patients__right">
            <button onclick="handleModal(1)" class="add_btn">
                <img src="/img/IconAddWhite.svg" alt="IconAdd">
                Добавить
            </button>
        </div>
    </div>

    <div class="patients__table">
        <table>
            <thead>
            <tr>
                <td>
                    <div class="filter_text table-filter">
                        <span>Категория</span>
                        <div class="select_wrapper">
                            <img src="/img/IconFilter.svg" alt="IconFilter">
                            <select class="pli-pl-filter">
                                <?php
                                if (!empty($priceLists)): ?>
                                    <option value=""></option>
                                    <?php
                                    foreach ($priceLists as $priceList): ?>
                                        <option <?= $data['price_list_id'] == $priceList->id ? 'selected' : '' ?>
                                                value="<?= $priceList->id ?>"><?= $priceList->section ?></option>
                                    <?php
                                    endforeach; ?>
                                <?php
                                endif; ?>
                            </select>
                            <img src="/img/IconArrowDown.svg" alt="IconFilter">
                        </div>
                    </div>
                </td>
                <td>
                    <div class="filter_text table-filter">
                        <span>Название</span>
                        <div class="select_wrapper">
                            <img src="/img/IconFilter.svg" alt="IconFilter">
                            <select class="pli-title-filter">
                                <option value="asc" <?= $data['sort'] == 'asc' ? 'selected' : '' ?>>По А-Я</option>
                                <option value="desc" <?= $data['sort'] == 'desc' ? 'selected' : '' ?>>По Я-А</option>
                            </select>
                            <img src="/img/IconArrowDown.svg" alt="IconFilter">
                        </div>
                    </div>
                </td>

                <td>
                    <div class="filter_text">
                        <span>услуга техника</span>
                    </div>
                </td>

                <td>
                    <div class="filter_text">
                        <span>Применять скидку</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Расходный материал</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Цена</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Действия</span>
                    </div>
                </td>
            </tr>
            </thead>
            <?php
            if (!empty($data['price_list_items'])): ?>
                <?php
                foreach ($data['price_list_items'] as $priceListItem): ?>
                    <tr>
                        <td>
                            <p>
                                <?= $priceListItem->priceList ? $priceListItem->priceList->section : '-' ?>
                            </p>
                        </td>

                        <td>
                            <p>
                                <?= $priceListItem->name ?>
                            </p>
                        </td>

                        <td>
                            <p>
                                <?= $priceListItem->technicianPriceList ? $priceListItem->technicianPriceList->name : null ?>
                            </p>
                        </td>

                        <td>
                            <p>
                                <?= $priceListItem->discount_apply
                                    ? '<p class="tex_green">Да</p>'
                                    : '<p class="tex_red">Нет</p>' ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= number_format($priceListItem->consumable, 0, '', ' ') ?> сум
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= number_format($priceListItem->price, 0, '', ' ') ?> сум
                            </p>
                        </td>

                        <td>
                            <a class="edit-price-list-btn" onclick="handleModal(3)" data-id="<?= $priceListItem->id ?>">
                                <img src="/img/scheduleNew/iconEdit.svg" alt="">
                            </a>
                            <a onclick="handleModal(2)" data-id="<?= $priceListItem->id ?>" class="price-list-delete-btn">
                                <img src="/img/scheduleNew/iconDelete.svg" alt="">
                            </a>
                        </td>
                    </tr>
                <?php
                endforeach; ?>
            <?php
            endif; ?>
        </table>
        <?= $this->render('_pagination', ['data' => $data]) ?>
    </div>
</div>

<div id='modalWrapper' class="price-modal__wrapper">

    <?= $this->render('_new-price-list-modal.php', [
        'priceLists' => $priceLists,
        'technicianPriceLists' => $technicianPriceLists
    ]) ?>

    <?= $this->render('_edit-price-list-modal.php') ?>

    <div id="cardTwo" class="modal-card">
        <div class="card-head">
            <p>Удалить услугу</p>
            <button id='modal_close'>
                <img src="../img/IconClose.svg" alt="IconClose">
            </button>
        </div>

        <div class="modal-body">
            <div class="warning-card">
                <img src="/img/warning-img.svg" alt="warning-img">
                <p>Вы уверены что хотите <br> удалить услугу?</p>
            </div>
        </div>
        <div class="modal-footer delete-modal__footer">
        <?= Html::a("Да", ['price-list-item/delete'], [
                'class' => 'btn btn-yes price-list-delete',
                'data' => [
                    'method' => 'post'
                ]
            ]) ?>
            <button id='modal_close' class=" btn btn-no">                       
                Нет
            </button>
        </div>
    </div>
</div>
