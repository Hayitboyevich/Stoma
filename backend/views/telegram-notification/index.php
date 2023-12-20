<?php

/* @var $notifications common\models\TelegramNotification */

$this->title = 'Уведомления Telegram';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="telegram-table">
    <div class="telegram-table-header">
        <h2 class="telegram-table-title">Уведомления Telegram</h2>
    </div>
    <!--  table -->
    <div class="telegram-table__table" style="height:calc(100% - 35px); overflow-y: auto;">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>#</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Message</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>User ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Patient ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Response</span>
                    </div>
                </td>
            </tr>

            <?php foreach ($notifications as $notification): ?>
                <tr class="tr">
                    <td class="table__body-td">
                        <p>
                            <?= $notification->id ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $notification->message ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $notification->user_id ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $notification->patient_id ?>
                        </p>
                    </td>
                    <td class="table__body-td">
                        <p>
                            <?= $notification->response ?>
                        </p>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
    </div>

    <!--    pagination -->
<!--    <div class="patients__pagination">-->
<!--        <div class="pagination__left">-->
<!--            <span>1-10</span>-->
<!--            <span>of</span>-->
<!--            <span>97</span>-->
<!--        </div>-->
<!--        <div class="pagination__right">-->
<!--            <div class="pagination_right_select">-->
<!--                <span>Rows per page:</span>-->
<!--                <select>-->
<!--                    <option>10</option>-->
<!--                    <option>10</option>-->
<!--                </select>-->
<!--            </div>-->
<!--            <div class="pagination_right_btn">-->
<!--                <button type="submit">-->
<!--                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                        <path d="M9.5 11L6.5 8L9.5 5" stroke="#868FA0" stroke-width="1.5" stroke-linecap="round"-->
<!--                              stroke-linejoin="round"/>-->
<!--                    </svg>-->
<!--                </button>-->
<!--                <div class="pagination_right_btn_number">-->
<!--                    <span>1/</span>-->
<!--                    <span>10</span>-->
<!--                </div>-->
<!--                <button type="submit" class="button_active">-->
<!--                    <svg width="6" height="8" viewBox="0 0 6 8" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                        <path d="M1.5 7L4.5 4L1.5 1" stroke="#464F60" stroke-width="1.5" stroke-linecap="round"-->
<!--                              stroke-linejoin="round"/>-->
<!--                    </svg>-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

</div>