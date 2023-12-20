<?php

/** @var array $data */

use yii\helpers\Url;

?>

<!--    pagination -->
<form action="<?= Url::to(['cashier/stats']) ?>"
      style="display: <?= $data['show_pagination'] ? 'block' : 'none' ?>">
    <input type="hidden" name="page" value="<?= $data['page'] ?>"/>
    <div class="patients__pagination ">
        <div class="pagination__left">
            <span><?= $data['offset'] + 1 ?>-<?= $data['last_row_number'] ?></span>
            <span>of</span>
            <span><?= $data['total_rows'] ?></span>
        </div>
        <div class="pagination__right">
            <div class="pagination_right_select">
                <span>Показывать по:</span>
                <select name="per_page">
                    <?php
                    for ($i = 10; $i <= 50; $i += 10): ?>
                        <option <?= $i == $data['per_page'] ? 'selected' : '' ?>><?= $i ?></option>
                    <?php
                    endfor; ?>
                </select>
            </div>
            <div class="pagination_right_btn">
                <button class="prev-page <?= $data['page'] > 1 ? 'button_active' : '' ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="pagination_right_btn_number">
                    <span><?= $data['page'] ?>/</span>
                    <span><?= $data['total_pages'] ?></span>
                </div>
                <button class="next-page <?= $data['page'] < $data['total_pages'] ? 'button_active' : '' ?>">
                    <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 7L4.5 4L1.5 1" stroke="#464F60" stroke-width="1.5" stroke-linecap="round"
                              stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</form>
