<?php

/** @var array $data */
/** @var PriceList $priceLists */
/** @var TechnicianPriceList $technicianPriceLists */

use common\models\PriceList;
use common\models\TechnicianPriceList;

$this->title = 'Услуги';

?>

<div id="cardOne" class="modal-card">
    <div class="card-head">
        <p>Добавить услугу</p>
        <button id='modal_close'>
            <img src="../img/IconClose.svg" alt="IconClose">
        </button>
    </div>

    <div class="modal-body">
        <div class="input-wrapper">
            <label>Раздел</label>
            <select class="price_list_id">
                <option value="0" selected disabled>Выберите раздел</option>
                <?php if (!empty($priceLists)): ?>
                    <?php foreach ($priceLists as $priceList): ?>
                        <option value="<?= $priceList->id ?>"><?= $priceList->section ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="input-wrapper">
            <label>Название</label>
            <input type="text" class="name" placeholder="Введите название">
        </div>
        <div class="input-wrapper">
            <label>Цена</label>
            <input type="number" class="price" placeholder="Введите цену">
        </div>
        <div class="input-wrapper">
            <label>Расходный материал</label>
            <input type="number" class="consumable" placeholder="0">
        </div>
        <div class="input-wrapper">
            <label>услуга техника</label>
            <select class="technician_price_list_id">
                <option value="0" selected disabled>Выберите услуга техника</option>
                <?php if (!empty($technicianPriceLists)): ?>
                    <?php foreach ($technicianPriceLists as $technicianPriceList): ?>
                        <option value="<?= $technicianPriceList->id ?>"><?= $technicianPriceList->name ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="input-wrapper">
            <label>Применять скидку</label>

            <div class="radio-wrapper">
                <label>
                    <input type="radio" class="discount_apply" checked name="sale" value="1">
                    Да
                </label>

                <label>
                    <input type="radio" class="discount_apply" name="sale" value="0">
                    Нет
                </label>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="add-btn save-price-list-item">
            добавить
        </button>
    </div>
</div>