<?php
/** @var boolean $login_error */

?>
<!-- Авторизация  -->
<div class="login__wrapper">
    <div class="login__wrapper_logo">
        <img src="/img/logoBig.svg" alt="">
    </div>
    <div class="login__wrapper_form">
        <h2>Войти</h2>
        <?php
        use yii\widgets\ActiveForm;

        $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <label for="login">
            Логин
            <input name="LoginForm[username]" id="login" type="text"/>
        </label>
        <label for="parol">
            Пароль
            <input name="LoginForm[password]" id="parol" type="password"/>
        </label>
        <a class="login__wrapper_form_link" href="/site/reset-password">Забыл пароль</a>
        <?php
        if ($login_error): ?>
            <div class="login_error">Неправильный логин или пароль</div>
        <?php
        endif; ?>

        <div class="login__wrapper_form_btn">
            <button name="login-button" type="submit" class="btn-reset">Войти</button>
        </div>
        <?php
        ActiveForm::end(); ?>

    </div>
    <div class="login__wrapper__img">
        <img src="/img/loginFormimg.png" alt="">
    </div>
</div>

<!-- Авторизация  -->
