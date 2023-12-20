<div id="cardThree" class="modal-card">
    <div class="card-head">
        <p>Редактировать услугу</p>
        <button id='modal_close'>
            <img src="../img/IconClose.svg" alt="IconClose">
        </button>
    </div>

    <div class="modal-body">
        <div class="input-wrapper">
            <label>Раздел</label>
            <select class="price_list_id">
                <option value="0" selected disabled>Выберите раздел</option>
            </select>
        </div>
        <div class="input-wrapper">
            <label>Название</label>
            <input type="text" placeholder="Введите название">
        </div>
        <div class="input-wrapper">
            <label>Цена</label>
            <input type="text" placeholder="Введите цену">
        </div>
        <div class="input-wrapper">
            <label>Расходный материал</label>
            <input type="number" placeholder="0">
        </div>
        <div class="input-wrapper">
            <label>услуга техника</label>
            <select>
                <option value="0" selected disabled>Выберите услуга техника</option>
            </select>
        </div>

        <div class="input-wrapper">
            <label>Применять скидку</label>

            <div class="radio-wrapper">
                <label>
                    <input type="radio" name="sale">
                    Да
                </label>

                <label>
                    <input type="radio" name="sale">
                    Нет
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="add-btn save-edit-price-list-item">
            Сохранять
        </button>
    </div>
</div>