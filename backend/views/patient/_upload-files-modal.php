<?php
/**@var \common\models\Patient $model */
?>




<div class="upload-files-modal">
    <div class="upload-files-form-wrap">
        <div class="modal__upload">
            <input type="hidden" id="upload_patient_id" value="<?= $model->id ?>"/>

            <div class="modal__upload-header">
                <h3 class="modal__upload-header-title">загрузка файлов пациента</h3>
                <img src="/img/scheduleNew/IconClose.svg" onclick="closeModal('.upload-files-modal')" alt="">
            </div>
            <div class="modal__upload-body">
                <label class="modal__upload-label">
                    заголовок
                    <input type="text" id="file_title" placeholder="Введите заголовок">
                </label>


                    <form id="fileForm" class="modal__upload-label upload" action="#">
                      
                      <input class="file-input" type="file" name="file" hidden >
                        <img src="/img/scheduleNew/fileIcon.svg" alt="" width="40px">        
                        <p class="modal__upload-label-desc">
                            Перетащите файл сюда или <br> <span> выберите из файлов</span>
                        </p>                
                    </form>
                    <section class="progress-area"></section>
                    <section class="uploaded-area"></section>

                <!-- <label class="modal__upload-label upload">
                    <img src="/img/scheduleNew/fileIcon.svg" alt="" width="40px">
                    <p class="modal__upload-label-desc">
                        Перетащите файл сюда или <br> <span> выберите из файлов</span>
                    </p>
                    <input type="file" id="select_file" class="modal__upload-label-file">
                </label> -->

                <label class="modal__upload-label">
                    описание
                    <textarea id="file_description" cols="30" rows="10" placeholder="Введите описание"></textarea>
                </label>

                <!-- <div class="progress" id="progress_bar" style="display:none; ">
                    <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0">0%</div>
                </div> -->
                <!-- <div id="uploaded_image" class="row mt-5"></div> -->
            </div>

            <div class="modal__upload-footer">
                <button type="button" class="btn-reset btn-blue" id="start-upload-file-btn">Сохранить</button>
            </div>
        </div>
    </div>
</div>
