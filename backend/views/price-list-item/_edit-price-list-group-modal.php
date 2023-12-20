<?php

/** @var PriceListItem $priceListItemsList */

/** @var PriceListItem $model */

use common\models\PriceListItem;

?>

    <input type="hidden" id="idInput" value="<?= $model->id ?>">

    <div class="input-wrapper">
        <label for="textInput">Название группы</label>
        <input class="textInput" id="nameInput" value="<?= $model->name ?>" type="text"
               placeholder="Введите название группы">
    </div>

    <div class="input-wrapper mt-3">
        <div mbsc-page class="demo-multiple-select">
            <label class="select_label">
                Услуга
                <input class="select_input" style="width: 100%" mbsc-input id="demo-multiple-select-input2"
                       placeholder="Выберите услугу" data-dropdown="true" data-input-style="outline"
                       data-label-style="stacked" data-tags="true" />
            </label>
            <select id="demo-multiple-select2" multiple>
                <?php if (!empty($priceListItemsList)): ?>
                    <?php foreach ($priceListItemsList as $item): ?>
                        <option <?= $item->parent_id === $model->id ? 'selected' : '' ?> value="<?= $item->id ?>"><?= $item->name ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

<?php
$js = <<<JS
    mobiscroll.select("#demo-multiple-select2", {
        inputElement: document.getElementById("demo-multiple-select-input2")
    });
JS;
$this->registerJs($js);
?>

