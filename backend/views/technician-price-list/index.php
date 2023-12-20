<?php

/** @var TechnicianPriceList $model */

use common\models\TechnicianPriceList;

?>
<div class="employee">
    <!-- search -->
    <div class="employee__search">
        <div class="employee__left">
            <button class="btn_top remove-selected-technicians">Удалить</button>
        </div>
        <div class="employee__right">
            <button class="btn_top btn_blue new-technician-btn">
                Добавить <span><img src="/img/icon_plus.svg" alt=""></span>
            </button>
        </div>
    </div>
    <!--  table -->
    <div class="employee__table" style="height:calc(100% - 150px); overflow-y: auto">
        <table cellspacing="0">
            <tr>
                <td></td>
                <td>
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>НАЗВАНИЕ</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>ЦЕНА</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>ДЕЙСТВИЯ</span>
                    </div>
                </td>
            </tr>
            <?php
            if ($model !== null): ?>
                <?php
                foreach ($model as $item): ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="remove-technician-select" data-id="<?= $item->id ?>"/>
                        </td>
                        <td>
                            <p><?= $item->id ?></p>
                        </td>
                        <td>
                            <p><?= $item->name ?></p>
                        </td>
                        <td>
                            <p><?= number_format($item->price, 0, '', ' ') ?> сум</p>
                        </td>
                        <td>
                            <div class="employee__table__actions">
                                <button type="button" style="background: none; border: none; cursor: pointer"
                                        class="edit-technician-price-list-btn"
                                        data-id="<?= $item->id ?>">
                                    <img src="/img/pen_icon.svg">
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php
                endforeach; ?>
            <?php
            endif; ?>
        </table>
    </div>
</div>

<?= $this->render('_new-technician-price-list') ?>
<?= $this->render('_update-technician-price-list') ?>
