<?php

/**@var Patient $model */

/**@var Media $file */

use common\models\Media;
use common\models\Patient;
use yii\helpers\Url;

?>

<div class="bill">
    <!--  table -->
    <div class="bill__table" style="height:calc(100vh - 250px); overflow-y: auto">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Заголовок</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Описание</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Время загрузки</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Действие</span>
                    </div>
                </td>
            </tr>

            <?php if (!empty($model->files)): ?>
                <?php foreach ($model->files as $file): ?>
                    <tr>
                        <td class="table__body-td">
                            <p><?= $file->id ?></p>
                        </td>
                        <td class="table__body-td">
                            <p><?= $file->title ?></p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= !empty($file->description)
                                    ? $file->description : '-' ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p><?= date('d.m.Y', strtotime($file->uploaded_at)) ?></p>
                        </td>
                        <td class="table__body-td">
                            <div class="table__body-td-icon">
                                <a target="_blank" href="<?= Yii::$app->urlManager->createUrl(['patient/view-file', 'id' => $file->id]) ?>">
                                    <img src="/img/scheduleNew/eye.svg" alt="">
                                </a>
                                <a href="<?= Url::to(['media/download', 'id' => $file->id]) ?>">
                                    <img src="/img/scheduleNew/download.svg" alt="">
                                </a>
                                <a href="#" class="patient-file-delete" data-id="<?= $file->id ?>">
                                    <img src="/img/scheduleNew/iconDelete.svg" alt="">
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Нет файлов</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <div class="bill__table-footer">
        <button class="btn-reset upload-patient-files-btn btn__patient-add">
            Добавить <img src="/img/scheduleNew/IconAdd.svg" alt="" width="16"/>
        </button>
    </div>
</div>
<?= $this->render('_upload-files-modal', ['model' => $model]) ?>
