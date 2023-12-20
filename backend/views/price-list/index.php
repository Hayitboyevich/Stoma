<?php

use common\models\PriceList;
use common\models\PriceListItem;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var PriceList $categories */
/** @var PriceListItem $priceListItems */
/** @var PriceListItem $priceListItemsList */
/** @var PriceList $model */
/** @var int $categoryId */

$this->title = 'Прайс-лист';

?>

<div class="price">
    <div class="price__top">
        <h1 class='title'>
            Прайс-лист
        </h1>
        <!--        <div class="price-top__left">-->
        <!--            <div class="top-left__date">-->
        <!--                <span>Дата активации</span>-->
        <!--                <p>09.09.2023 <b>19:45:56</b></p>-->
        <!--            </div>-->
        <!--            <button class="edit-btn">-->
        <!--                <img src="/img/IconEdit.svg" alt="IconEdit">-->
        <!--            </button>-->
        <!--        </div>-->
    </div>

    <div class='price_boxes for-desktop'>
        <div class="box price__left">
            <div class="box_head">
                <p class="head_title">Разделы</p>

                <div class="buttons">
                    <a href="<?= Url::to(['price-list/excel']) ?>" class="download_excel">
                        <img src="/img/IconExcel.svg" alt="IconExcel">
                    </a>
                    <button onclick="handleModal(1)" class='add_btn'>
                        <img src="/img/IconAdd.svg" alt="IconAdd">
                    </button>
                </div>
            </div>
            <div class="box_body">
                <?php
                if ($categories !== null): ?>
                    <?php
                    foreach ($categories as $category): ?>
                        <div class="box-body__card <?= $category->id === $categoryId ? 'active' : '' ?>">
                            <a href="<?= Url::to(['price-list/index', 'categoryId' => $category->id]) ?>"
                               class="card_text">
                                <?= $category->section ?>
                            </a>
                            <div class="card_buttons">
                                <button onclick="handleModal(3)" class="edit-price-list-button"
                                        data-id="<?= $category->id ?>">
                                    <img src="/img/IconEditGrey.svg" alt="IconEditGrey">
                                </button>
                                <?= Html::a("<img src='/img/IconTrash.svg'>", ['delete', 'id' => $category->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                        'method' => 'post',
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    <?php
                    endforeach; ?>
                <?php
                endif; ?>
            </div>
        </div>
        <?php
        if ($model): ?>
            <div class="box price__right ">
                <div class="box_head">
                    <p class="head_title">Группы и позиции</p>
                    <div class="buttons">
                        <a href="<?= Url::to(['price-list-item/excel', 'categoryId' => $model->id]) ?>"
                           class="download_excel">
                            <img src="/img/IconExcel.svg" alt="IconExcel">
                        </a>
                        <a class='edit_btn' href="<?= Url::to(['price-list-item/index']) ?>">
                            <img src="/img/IconEditSmall.svg" alt="IconEditSmall">
                        </a>
                        <button class='add_btn' onclick="handleModal(2)">
                            <img src="/img/IconAdd.svg" alt="IconAdd">
                        </button>
                    </div>
                </div>
                <div class="box_body">
                    <?php
                    if ($priceListItems !== null): ?>
                        <?php
                        foreach ($priceListItems as $priceListItem): ?>
                            <?php
                            if (!empty($priceListItem->priceListItems)): ?>
                                <div class="accordion">
                                    <div class="accordion__slot">
                                        <button class="accordion__button">
                                            <img src="/img/accordion-arrow.svg" alt="IconArrowDown"/>
                                            <?= $priceListItem->name ?>
                                        </button>
                                        
                                        <div class="accordion_slot-right" >
                                        <button onclick="handleModal(4)" class="edit-price-list-group-button"
                                                data-id="<?= $priceListItem->id ?>">
                                            <img src="/img/IconEditGrey.svg" alt="IconEditGrey">
                                        </button>
                                        <button>
                                        <?= Html::a(
                                            "<img src='/img/IconTrash.svg'>",
                                            ['price-list-item/delete', 'id' => $priceListItem->id],
                                            [
                                                'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                                    'method' => 'post',
                                                ]
                                            ]
                                        ) ?>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="accordion__slot">
                                        <div class="accordion__panel">
                                            <div class="accordion-card__wrapper">
                                                <?php
                                                foreach ($priceListItem->priceListItems as $listItem): ?>
                                                    <div class="accordion-card">
                                                        <p><?= $listItem->name ?></p>

                                                        <div class="card-left">
                                                            <span>
                                                                <?= number_format($listItem->price, 0, '', ' ') ?> сум
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php
                                                endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            else: ?>
                                <div class="box-body__card">
                                    <p class="card_text"><?= $priceListItem->name ?></p>
                                    <?php
                                    if (!$priceListItem->is_group): ?>
                                        <span>
                                            <?= number_format($priceListItem->price, 0, '', ' ') ?> сум
                                        </span>
                                    <?php
                                    endif; ?>
                                </div>
                            <?php
                            endif; ?>
                        <?php
                        endforeach; ?>
                    <?php
                    endif; ?>
                </div>
            </div>
        <?php
        endif; ?>
    </div>

    <div class='price_boxes for-mobile'>
        <div class="box price__left">
            <div class="box_head">
                <p class="head_title">Разделы</p>

                <div class="buttons">
                    <a href="<?= Url::to(['price-list/excel']) ?>" class="download_excel">
                        <img src="/img/IconExcel.svg" alt="IconExcel">
                    </a>
                    <button onclick="handleModal(1)" class='add_btn'>
                        <img src="/img/IconAdd.svg" alt="IconAdd">
                    </button>
                </div>
            </div>
            <div class="box_body">
                <?php if ($categories !== null): ?>
                    <?php foreach ($categories as $category): ?>
                        <div class="box-body__card <?= $category->id === $categoryId ? 'active' : '' ?>">
                            <a href="<?= Url::to(['price-list/view', 'id' => $category->id]) ?>" class="card_text">
                                <?= $category->section ?>
                            </a>
                            <div class="card_buttons">
                                <button onclick="handleModal(3)" class="edit-price-list-button" data-id="<?= $category->id ?>">
                                    <img src="/img/IconEditGrey.svg" alt="IconEditGrey">
                                </button>
                                <?= Html::a("<img src='/img/IconTrash.svg'>", ['delete', 'id' => $category->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                        'method' => 'post',
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="modalWrapper" class="price-modal__wrapper">

        <?= $this->render('_new-price-list-modal') ?>

        <?php if ($model): ?>
            <?= $this->render('/price-list-item/_new-price-list-group-modal', [
                'priceListItemsList' => $priceListItemsList,
                'priceListId' => $model->id
            ]) ?>
        <?php endif; ?>

    </div>
</div>
