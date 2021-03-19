<?php

/* @var $this yii\web\View */

$this->title = 'Авторизация';
?>
<!-- Login Form -->
<div class="login-form-fw">
    <div class="login-form">
        <form method="post">
            <div class="title">Войти</div>
            <div class="group">
                <div class="field">
                    <input type="text" placeholder="Логин" />
                </div>
            </div>
            <div class="group">
                <div class="field">
                    <input type="text" placeholder="Пароль" />
                </div>
            </div>
            <input type="submit" value="Войти" >
            <div class="wrong-link">
                <a href="#">Получить пароль</a>
            </div>
        </form>
    </div>
</div>
