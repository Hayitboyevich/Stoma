<?php

/** @var int $priceListId */
/** @var PriceListItem $priceListItemsList */

use common\models\PriceListItem;

?>

<div id="cardTwo" class="modal_card card_two">
    <div class="modal_card__head">
        <p>Добавить Группы и услуги</p>
        <button id='modal_close'>
            <img src="/img/IconClose.svg" alt="IconClose">
        </button>
    </div>
    <div class="modal_card__body">
        <input id="priceListIdInput" type="hidden" value="<?= $priceListId ?>">

        <div class="input-wrapper">
            <label for="textInput">Название группы</label>
            <input class="textInput" id="textInput" type="text" placeholder="Введите название группы">
        </div>

        <div class="input-wrapper mt-3">
            <div mbsc-page class="demo-multiple-select">
                <label class="select_label">
                    Услуга
                    <input class="select_input" mbsc-input id="demo-multiple-select-input"
                           placeholder="Выберите услугу" data-dropdown="true" data-input-style="outline"
                           data-label-style="stacked" data-tags="true" />
                </label>
                <select id="demo-multiple-select" multiple>
                    <?php if (!empty($priceListItemsList)): ?>
                        <?php foreach ($priceListItemsList as $item): ?>
                            <option value="<?= $item->id ?>"><?= $item->name ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </div>
    <div  class="modal_card__footer">
        <button class="new-price-list-group-button">
            добавить
        </button>
    </div>
</div>

<div id="cardFour" class="modal_card card_two">
    <div class="modal_card__head">
        <p>Редактировать Группы и услуги</p>
        <button id='modal_close'>
            <img src="/img/IconClose.svg" alt="IconClose">
        </button>
    </div>
    <div class="modal_card__body">
        <input id="priceListIdInput" type="hidden" value="<?= $priceListId ?>">

        <div class="input-wrapper">
            <label for="textInput">Название группы</label>
            <input class="textInput" id="textInput" type="text" placeholder="Введите название группы">
        </div>

    </div>
    <div  class="modal_card__footer">
        <button class="save-edit-price-list-group-button">
            сохранить
        </button>
    </div>
</div>