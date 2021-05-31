<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use app\assets\DefaultAssets;
use app\assets\IeAssets;

DefaultAssets::register($this);
IeAssets::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->registerCsrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="HandheldFriendly" content="true">

        <link rel="preconnect" href="https://fonts.gstatic.com">

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl(); ?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo Yii::$app->getHomeUrl(); ?>favicon.ico" type="image/x-icon">
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <?php
    $sidebar = explode('||--||', \app\components\Summary::widget());
    ?>
    <div class="bg">

        <!-- Preloader -->
        <div class="preloader">
            <div class="centrize full-width">
                <div class="vertical-center">
                    <div class="spinner"></div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="header">
            <div class="fw">

                <!-- logo -->
                <div class="h-logo">
                    <a href="<?= \yii\helpers\Url::base(true); ?>">
                        <img src="/images/logo.png" alt=""/>
                    </a>
                </div>

                <div class="h-label"><?= Yii::$app->session->get('fullUserName') ?></div>

                <!-- menu -->
                <div class="top-menu">
                    <ul>
                        <li><?= Html::a('Профиль потребителя', ['main/profile']) ?></li> <!-- class="active" -->
                        <li class="children">
                            <a href="#">Заключение/изменение договора</a>
                            <ul>
                                <li><a href="#">Заключить договор</a></li>
                                <li><a href="#">Внести изменения в договор</a></li>
                                <li><a href="#">Ранее поданные заявки</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Помощь</a></li>
                        <li><a href="#">Диалоги <span>12</span></a></li>
                    </ul>
                </div>

                <!-- login -->
                <div class="h-login">

                    <?= Html::a(Yii::$app->session->get('fullUserName'), ['login/logout'], ['class' => 'h-login-btn']) ?>
                    <?= Html::a('Выйти из аккаунта', ['login/logout'], ['class' => 'h-login-btn-mobile']) ?>
                </div>

                <!-- menu btn -->
                <a href="#" class="menu-btn">
                    <span class="lines"></span>
                    <span class="num">12</span>
                </a>

            </div>
        </header>

        <!-- Sidebar -->
        <div class="sidebar-menu">
            <div class="sidebar-menu-fw">
                <ul>
                    <li>
                        <a href="<?= \yii\helpers\Url::base(true); ?>">Сводка</a>

                    </li>
                    <?= $sidebar[0] ?>

                </ul>
            </div>
        </div>

        <!-- Wrapper -->
        <div class="wrapper">

            <?= $content ?>

            <div class="clear"></div>
        </div>

        <!-- Footer -->
        <div class="footer"></div>

        <!-- Popups -->
        <div class="overlay"></div>

        <div class="contracts-devices-popup-overlay"></div>
        <div class="contracts-devices-popup">
            <div class="table">

            </div>
            <div class="close"></div>
        </div>
        <div class="contracts-edo-popup custom-popup">
            <h2>Выберите договор</h2>
            <ul>
                <?= $sidebar[1] ?>
            </ul>
            <div class="close"></div>
        </div>
        <div class="loading-report-popup custom-popup">
            <h2>Внимание!</h2>
            <p>Вы указали большой расчетный период, формирование документов займет некоторое время.</p>
            <a href="#" class="btn submit-report">Далее</a>
            <a href="#" class="btn border new-date">Изменить</a>
            <div class="close"></div>
        </div>
    </div>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>