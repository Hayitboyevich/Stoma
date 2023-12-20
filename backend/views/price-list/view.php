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
    </div>
    <div class='price_boxes'>

    <div class='price_boxes-top' >
        <a href="<?= Url::to(['price-list/index']) ?>">
            <img src="/img/IconBack.svg" alt="IconBack">
        </a>
        <div>
            <span>Название раздела</span>
            <p><?= $model->section ?></p>
        </div>
    </div>
        <?php if ($model): ?>
            <div class="box price__right price__right-mobile">
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
                    <?php if ($priceListItems !== null): ?>
                        <?php foreach ($priceListItems as $priceListItem): ?>
                            <?php if (!empty($priceListItem->priceListItems)): ?>
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
                            <?php else: ?>
                                <div class="box-body__card">
                                    <p class="card_text"><?= $priceListItem->name ?></p>
                                    <?php if (!$priceListItem->is_group): ?>
                                        <span>
                                            <?= number_format($priceListItem->price, 0, '', ' ') ?> сум
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div id="modalWrapper" class="price-modal__wrapper">

        <?php if ($model): ?>
            <?= $this->render('/price-list-item/_new-price-list-group-modal', [
                'priceListItemsList' => $priceListItemsList,
                'priceListId' => $model->id
            ]) ?>
        <?php endif; ?>

    </div>
</div>
