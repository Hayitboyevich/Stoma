<?php

/**@var array $data */

$this->title = 'Логи удаления';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="patients history__log">
    <h3 class="wrapper__box-title">Логи удаления</h3>
    <div class="history__table" style="height:calc(100% - 80px); overflow-y: auto">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div class="filter_text">
                        <span>Имя</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Тип объекта</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>ID объекта</span>
                    </div>
                </td>
                <td>
                    <div class="filter_text">
                        <span>Дата</span>
                    </div>
                </td>
            </tr>

            <?php if (empty($data['delete_logs'])): ?>
                <tr>
                    <td colspan="4">
                        <p class="text_center-table">Нет данных</p>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($data['delete_logs'])): ?>
                <?php foreach ($data['delete_logs'] as $deleteLog): ?>
                    <tr>
                        <td>
                            <p class="history__log_info"><?= $deleteLog->name ?></p>
                        </td>
                        <td>
                            <p class="history__log_info"><?= $deleteLog->object_type ?></p>
                        </td>
                        <td>
                            <p class="history__log_info"><?= $deleteLog->object_id ?></p>
                        </td>
                        <td>
                            <p class="history__log_info"><?= date('Y-m-d H:i', strtotime($deleteLog->deleted_at)) ?></p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>

    <?= $this->render('_pagination', ['data' => $data]) ?>
</div>
