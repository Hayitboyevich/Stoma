<?php

use common\models\PriceList;
use common\models\PriceListItem;

/* @var $priceLists PriceList */

?>

<div class="modals-patients sj-modal-patients">
    <div class="modal-overlay">
        <div class="modal modal--1" data-target="form-popup">
            <div class="modal_top">
                <div class="modal__title">
                    <h2>Прайс-лист</h2>
                    <img id="close__modal" src="/img/modalClose.svg" alt="">
                </div>
                <div class="modal__body">
                    <div class="modal__body__search">
                        <img src="/img/search.svg" alt="">
                        <input type="text" placeholder="Поиск">
                    </div>
                    <div class="modal__body_accordion">
                        <div class="accordions">
                            <?php if (!empty($priceLists)): ?>
                                <?php foreach ($priceLists as $priceList): ?>
                                    <div class="accordion">
                                        <div class="accordion__slot">
                                            <button class="accordion__button">
                                                <?= $priceList->section ?>
                                                <span class="accordion_icon">
                                                    <svg width="8" height="6" viewBox="0 0 8 6" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M1 1.5L4 4.5L7 1.5" stroke="#ADB2BF" stroke-width="1.5"
                                                              stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                            </button>
                                            <?php if (!empty($priceList->items)): ?>
                                                <div class="accordion__slot">
                                                    <div class="accordion__panel">
                                                        <?php foreach ($priceList->items as $item):
                                                            if (!$item->is_group) { ?>
                                                            <div class="accordion__panel_item"
                                                                 data-service-id="<?= $item->id ?>">
                                                                <div class="accordion__panel_item_info">
                                                                    <p class="info__text">
                                                                        <?= $item->name ?>
                                                                    </p>
                                                                    <p class="text__bold"
                                                                        data-price="<?= $item->price ?>"
                                                                        data-discount-apply="<?= $item->discount_apply ?>">
                                                                        <?= number_format($item->price, 0, ' ', ' ') ?>
                                                                        сум
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                        <!-- sub accordion  -->
                                                            <div class="accordions">
                                                                <div class="sub-accordion">
                                                                    <div class="accordion__slot">
                                                                        <button class="sub-accordion__button">
                                                                                                                                    <img src="../img/accordion-arrow.svg" alt="IconArrowDown" />
                                                                                                                                    <?= $item->name ?>
                                                                        </button>
                                                                    </div>
                                                                    <div class="accordion__slot">
                                                                        <div class="sub-accordion__panel">
                                                                            <?php foreach ($item->priceListItems as $listItem) { ?>
                                                                            <div class="accordion__panel_item" data-service-id="<?= $listItem->id ?>">
                                                                                <div class="accordion__panel_item_info">
                                                                                    <p class="info__text">
                                                                                        <?= $listItem->name ?>
                                                                                    </p>
                                                                                    <p class="text__bold"
                                                                                        data-price="<?= $listItem->price ?>"
                                                                                        data-discount-apply="<?= $listItem->discount_apply ?>">
                                                                                        <?= number_format($listItem->price, 0, ' ', ' ') ?>
                                                                                        сум
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--  -->
                                                        <?php } ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal_bottom">
                <div class="modal__footer">
                    <button class="btn-reset" type="submit" onclick="addServiceToInvoice()">Добавить услугу</button>
                </div>
            </div>
        </div>
    </div>
</div>
