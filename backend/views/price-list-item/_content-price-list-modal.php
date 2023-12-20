<?php

/** @var PriceList $priceLists */
/** @var PriceListItem $model */
/** @var TechnicianPriceList $technicianPriceLists */

use common\models\PriceList;
use common\models\PriceListItem;
use common\models\TechnicianPriceList;

?>


<input class="id" type="hidden" value="<?= $model->id ?>">

<div class="input-wrapper">
    <label>Раздел</label>
    <select class="price_list_id">
        <option value="0" selected disabled>Выберите раздел</option>
        <?php if (!empty($priceLists)): ?>
            <?php foreach ($priceLists as $priceList): ?>
                <option <?= $model->price_list_id === $priceList->id ? 'selected' : '' ?> value="<?= $priceList->id ?>"><?= $priceList->section ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>
<div class="input-wrapper">
    <label>Название</label>
    <input type="text" value="<?= $model->name ?>" class="name" placeholder="Введите название">
</div>
<div class="input-wrapper">
    <label>Цена</label>
    <input type="number" value="<?= $model->price ?>" class="price" placeholder="Введите цену">
</div>
<div class="input-wrapper">
    <label>Расходный материал</label>
    <input type="number" value="<?= $model->consumable ?>" class="consumable" placeholder="0">
</div>
<div class="input-wrapper">
    <label>услуга техника</label>
    <select class="technician_price_list_id">
        <option value="0" selected disabled>Выберите услуга техника</option>
        <?php if (!empty($technicianPriceLists)): ?>
            <?php foreach ($technicianPriceLists as $technicianPriceList): ?>
                <option <?= $model->technician_price_list_id === $technicianPriceList->id ? 'selected' : '' ?> value="<?= $technicianPriceList->id ?>"><?= $technicianPriceList->name ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<div class="input-wrapper">
    <label>Применять скидку</label>

    <div class="radio-wrapper">
        <label>
            <input type="radio" class="discount_apply" <?= $model->discount_apply ? 'checked' : '' ?> name="sale" value="1">
            Да
        </label>

        <label>
            <input type="radio" class="discount_apply" <?= !$model->discount_apply ? 'checked' : '' ?> name="sale" value="0">
            Нет
        </label>
    </div>
</div>