<?php /**@var $model common\models\PatientExamination*/ ?>
<div style="text-align: left;margin-bottom: 20px;">
    <img src="/img/logoIcon.svg" style="width: 40px;" />
    <img src="/img/logo.svg" style="width: 250px;" />
</div>
<div style="text-align: left;margin-bottom: 20px;position: absolute;right: 20px;top: 0;">
    <img src="/img/svg/kidsmile.svg" style="width: 200px;height: auto;"      />
</div>
<p style="font-size: 20px;">ОСМОТР ПЕДИАТРА</p>
<table class="sj-table">
    <tr>
        <td contentEditable="true" class="title-style">#ID</td>
        <td contentEditable="true" class="title-style">Пациент</td>
        <td contentEditable="true" class="title-style">Дата рождения</td>
        <td contentEditable="true" class="title-style">Визит</td>
    </tr>
    <tr>
        <td contentEditable="true"><?= $model->id; ?></td>
        <td contentEditable="true"><?= $model->patient->getFullName(); ?></td>
        <td contentEditable="true"><?= $model->patient->dob; ?></td>
        <td contentEditable="true"><?= $model->visit ? $model->visit->created_at : ''; ?></td>
    </tr>

    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Врач</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->doctor ? $model->doctor->lastname.' '.$model->doctor->firstname : ''; ?></td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Жалобы</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->complaints; ?></td>
    </tr>

    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Анамнез</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->anamnesis; ?></td>
    </tr>

    <tr>
        <td contentEditable="true" class="title-style">Вес</td>
        <td contentEditable="true" class="title-style">Рост</td>
        <td contentEditable="true" class="title-style">Окружность головы</td>
        <td contentEditable="true" class="title-style">Температура</td>

    </tr>
    <tr>
        <td contentEditable="true"><?= $model->weight; ?></td>
        <td contentEditable="true"><?= $model->height; ?></td>
        <td contentEditable="true"><?= $model->head_circumference; ?></td>
        <td contentEditable="true"><?= $model->temperature; ?></td>

    </tr>

    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Данные осмотра</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->inspection_data; ?></td>
    </tr>

    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Диагноз</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->diagnosis; ?></td>
    </tr>

    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Рекомендации</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->recommendations; ?></td>
    </tr>

    <tr>
        <td contentEditable="true" colspan="4" class="title-style">Дата и время</td>
    </tr>
    <tr>
        <td contentEditable="true" colspan="4"><?= $model->datetime; ?></td>
    </tr>
</table>
<p>*При последующих визитах пожалуйста не забудте взять данную выписку с собой</p>
<p>*Повторный осмотр в течение последующих 7 дней проводится бесплатно. Если вы обнаружили новые симптомы или состояние ребенка ухудшилось, незамедлительно обратитесь обратно в клинику.</p>
<script type="text/javascript">
    window.print();
</script>
<style type="text/css" media="print">
    @page {
        margin: 20px;  /* this affects the margin in the printer settings */
    }
</style>
<style type="text/css">
    body{
        font-family: Roboto;
    }

    body .sj-table tr td{
        padding: 5px;
    }
</style>
