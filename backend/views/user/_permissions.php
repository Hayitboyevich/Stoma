<?php

use common\models\PriceList;
use common\models\User;

$csrfTokenName = Yii::$app->request->csrfParam;

$csrfToken = Yii::$app->request->csrfToken;
/* @var $model User */
/* @var $cat PriceList */
?>
<div class="sj-card right-block">
    <input type="hidden" name="permissions-user-id" value="<?= $model->id ?>"/>
    <h2>Права</h2>
    <table class="sj-table">
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th></th>
        </tr>
        <?php $permissions = Yii::$app->authManager->getPermissions(); ?>
        <?php if (!empty($permissions)): ?>
            <?php foreach ($permissions as $permission): ?>
                <tr>
                    <td><?= $permission->name ?></td>
                    <td><?= $permission->description ?></td>
                    <td>
                        <input class="check-perm" data-permission="<?= $permission->name ?>"
                               data-user-id="<?= $model->id ?>"
                               type="checkbox" <?= Yii::$app->authManager->checkAccess($model->id, $permission->name)
                            ? 'checked'
                            : '' ?> />
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</div>
