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
                    <a href="<?= \yii\helpers\Url::base(true); ?>" class="ploader">
                        <img src="/images/logo.svg" alt=""/>
                    </a>
                </div>

                <div class="h-label"><? \Yii::$app->controller->userName ?></div>

                <!-- menu -->
                <div class="top-menu">
                    <ul>
                        <li><?= Html::a('Профиль потребителя', ['main/profile'], ['class' => 'ploader']) ?></li>
                        <!-- class="active" -->
                        <!--                        <li class="children">-->
                        <!--                            <a href="#">Заключение/изменение договора</a>-->
                        <!--                            <ul>-->
                        <!--                                <li><a data-fancybox data-src="#hidden-content" href="javascript:;">Заключить договор</a></li>-->
                        <!--                                <li><a href="#">Внести изменения в договор</a></li>-->
                        <!--                                <li><a href="#">Ранее поданные заявки</a></li>-->
                        <!--                            </ul>-->
                        <!--                        </li>-->
                        <li class="children"><a data-fancybox data-src="#hidden-content" href="javascript:;">Заключение/изменение
                                договора</a></li>
                        <li><?= Html::a('Помощь', ['inner/help'], ['class' => 'ploader']) ?></li>
                        <li><?= Html::a('Написать обращение', ['inner/fos'], ['class' => 'ploader']) ?></li>
                    </ul>
                </div>

                <!-- login -->
                <div class="h-login">

                    <?= Html::a(\Yii::$app->controller->userName, ['login/logout'], ['class' => 'h-login-btn']) ?>
                    <?= Html::a('Выйти из аккаунта', ['login/logout'], ['class' => 'h-login-btn-mobile']) ?>
                </div>

                <!-- menu btn -->
                <a href="#" class="menu-btn">
                    <span class="lines"></span>
                    <!--                    <span class="num">12</span>-->
                </a>

            </div>
        </header>

        <!-- Sidebar -->
        <div class="sidebar-menu">
            <div class="sidebar-menu-fw">
                <ul>
                    <li>
                        <a href="<?= \yii\helpers\Url::base(true); ?>" class="ploader">Главная</a>

                    </li>
                    <?= \app\components\Summary::widget() ?>

                </ul>
            </div>
        </div>

        <!-- Wrapper -->
        <div class="wrapper text">

            <?= $content ?>

            <div class="clear"></div>
        </div>

        <!-- Footer -->
        <div class="footer"></div>

        <!-- Popups -->
        <div class="overlay"></div>

        <div class="contracts-devices-popup-overlay"></div>

    </div>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>