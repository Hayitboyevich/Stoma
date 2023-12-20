<?php

use common\models\TechnicianPriceList;

/* @var $model TechnicianPriceList */
?>
<div class="technician-modal-wrap">
    <div class="technician-modal-fixed">
        <div class="inline-form-wrap">
            <label>Название</label>
            <input value="" autocomplete="off" name="name" type="text"/>
        </div>
        <div class="inline-form-wrap" style="float: right">
            <label>Цена</label>
            <input value="" autocomplete="off" name="price" type="number"/>
        </div>
        <br/>
        <br/>
        <button class="new-technician-price-list-button">
            Сохранить
        </button>
    </div>
</div>
