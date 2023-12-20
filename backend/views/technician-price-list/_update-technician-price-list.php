<?php

use common\models\TechnicianPriceList;

/* @var $model TechnicianPriceList */
?>
<div class="technician-update-modal-wrap">
    <div class="technician-update-modal-fixed">
        <input type="hidden" name="id" value=""/>
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
        <button class="edit-technician-price-list-button">
            Сохранить
        </button>
    </div>
</div>

