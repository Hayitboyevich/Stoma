<?php

/**@var Patient $model */

/**@var PatientExamination $examination */

use common\models\Patient;
use common\models\PatientExamination;
use yii\helpers\Url;

?>


<div class="inspections-table">
    <!--  table -->
    <div class="inspections-table__table" style="height:calc(100vh - 278px); overflow-y: auto">
        <table cellspacing="0">
            <tr>
                <td class="table_head">
                    <div class="filter_text">
                        <span>#ID</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Визит</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Врач</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Жалобы</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Анамнез</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Вес</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Рост</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Окружность головы</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Температура</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Данные осмотра</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Диагноз</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Рекомендации</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Дата и время</span>
                    </div>
                </td>
                <td class="table_head">
                    <div class="filter_text">
                        <span>Печать</span>
                    </div>
                </td>
            </tr>
            <!-- <tr>
                <td class="table__body-td">
                    <p>
                        1
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <div class="anamnesis">
                        <p>В частности, понимание сути ресурсосберегающих технологий обеспечивает широкому кругу
                            (специалистов) участие в формировании позиций, занимаемых участниками в отношении
                            поставленных задач. В целом, конечно, начало повседневной работы по формированию позиции
                            создаёт необходимость включения в производственный план целого ряда внеочередных мероприятий
                            с учётом комплекса стандартных подходов. Приятно, граждане, наблюдать, как базовые сценарии
                            поведения пользователей призывают нас к новым свершениям, которые, в свою очередь, должны
                            быть указаны как претенденты на роль ключевых факторов. Как принято считать, акционеры
                            крупнейших компаний могут быть описаны максимально подробно. Равным образом, глубокий
                            уровень погружения представляет собой интересный эксперимент проверки системы обучения
                            кадров, соответствующей насущным потребностям.</p>
                        <span class="anamnesis_tooltip">
                            В частности, понимание сути ресурсосберегающих технологий обеспечивает широкому кругу
                            (специалистов) участие в формировании позиций, занимаемых участниками в отношении
                            поставленных задач. В целом, конечно, начало повседневной работы по формированию позиции
                            создаёт необходимость включения в производственный план целого ряда внеочередных мероприятий
                            с учётом комплекса стандартных подходов. Приятно, граждане, наблюдать, как базовые сценарии
                            поведения пользователей призывают нас к новым свершениям, которые, в свою очередь, должны
                            быть указаны как претенденты на роль ключевых факторов. Как принято считать, акционеры
                            крупнейших компаний могут быть описаны максимально подробно. Равным образом, глубокий
                            уровень погружения представляет собой интересный эксперимент проверки системы обучения
                            кадров, соответствующей насущным потребностям.
                        </span>
                    </div>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        test
                    </p>
                </td>
                <td class="table__body-td">
                    <p>
                        <a href="#">
                            <img src="/img/print.svg" style="width: 30px; height: 30px" />
                        </a>
                    </p>
                </td>
            </tr> -->
            <?php if (!empty($model->examinations)): ?>
                <?php foreach ($model->examinations as $examination): ?>
                    <tr>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->id; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->visit ? date('d.m.Y', strtotime($examination->visit->created_at)) : ''; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->doctor ? $examination->doctor->getFullName() : ''; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->complaints; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <div class="anamnesis">
                                <p><?= $examination->anamnesis; ?></p>
                                <span class="anamnesis_tooltip">
                                <?= $examination->anamnesis; ?>
                                </span>
                            </div>
                           
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->weight; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->height; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->head_circumference; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->temperature; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->inspection_data; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->diagnosis; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->recommendations; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <?= $examination->datetime; ?>
                            </p>
                        </td>
                        <td class="table__body-td">
                            <p>
                                <a target="_blank" href="/print/examination?id=<?= $examination->id ?>">
                                    <img src="/img/print.svg" style="width: 30px; height: 30px" />
                                </a>
                            </p>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="14">
                        <p class="text_center-table">нет записей</p>
                    </td>
                </tr>                
            <?php endif; ?>
        </table>
    </div>

    <?php if (Yii::$app->user->can('new_examination_create')): ?>
        <div class="inspections-table-footer">
            <a class="btn-reset" href="<?= Url::to(['patient/new-examination-create', 'patientId' => $model->id]) ?>">
                Добавить <img src="/img/scheduleNew/IconAdd.svg" alt="" width="16">
            </a>
        </div>
    <?php endif; ?>
</div>