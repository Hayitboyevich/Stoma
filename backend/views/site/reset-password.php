<!-- Авторизация  -->
<div class="login__wrapper">
    <a href="/" class="login__wrapper_logo">
        <img src="/img/logoBig.svg" alt="">
    </a>
    <div class="login__wrapper_form">
        <h2>Восстановление доступа</h2>
        <?php use yii\widgets\ActiveForm;

        $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <div class="reset-password-login">
            <label for="login">
                номер телефона
                <input name="LoginForm[username]" id="login" type="text" />
            </label>

            <div class="login__wrapper_form_btn">
                <button id="send-sms-code" type="submit" class="btn-reset">Отправить код</button>
            </div>
        </div>
        <div class="reset-password-new-password">
            <label for="login">
                Код из смс
                <input id="sms-code" type="text" />
            </label>
            <label for="login">
                Пароль
                <input id="password1" type="text" />
            </label>
            <label for="login">
                Повторить пароль
                <input id="password2" type="text" />
            </label>

            <div class="login__wrapper_form_btn">
                <button id="change-password-btn" type="submit" class="btn-reset">Изменить пароль</button>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="login__wrapper__img">
        <img src="/img/loginFormimg.png" alt="">
    </div>
</div>

<!-- Авторизация  -->