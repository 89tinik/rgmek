<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use app\assets\DefaultAssets;
use app\assets\IeAssets;
use app\modules\admin\assets\AdminAssets;

DefaultAssets::register($this);
IeAssets::register($this);
AdminAssets::register($this);

?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>АДМИН | <?= Html::encode($this->title) ?></title>
        <?php $this->registerCsrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="HandheldFriendly" content="true">
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="x-rim-auto-match" content="none">

        <link rel="preconnect" href="https://fonts.gstatic.com">

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl(); ?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo Yii::$app->getHomeUrl(); ?>favicon.ico" type="image/x-icon">
        <?php $this->head() ?>
    </head>

    <body class="operator">
    <?php $this->beginBody() ?>
    <div class="bg">
        <div class="fw">
            <!-- Preloader -->
            <div class="preloader">
                <div class="centrize full-width">
                    <div class="vertical-center">
                        <div class="spinner"></div>
                        <div class="message"><h3>Не удалось связаться с базой данных, попробуйте позже</h3><a href="#" class="spiner-close btn">Отмена</a></div>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <header class="header">
                <div class="fw">

                    <!-- logo -->
                    <div class="h-logo">
                        <a href="<?=\yii\helpers\Url::to(['/admin'])?>" class="ploader">
                            <img src="/images/logo.svg" alt=""/>
                        </a>
                    </div>

                    <div class="h-label"><? \Yii::$app->controller->userName ?></div>
                    <div class="top-menu">
                        <ul>
                            <li><?= Html::a('Пользователи', ['/admin'], ['class' => 'ploader']) ?></li>
                            <li><?= Html::a('Счета', ['/admin/invoice'], ['class' => 'ploader']) ?></li>
                            <li><?= Html::a('Банеры', ['/admin/baner'], ['class' => 'ploader']) ?></li>
                            <li><?= Html::a('Темы обращений', ['/admin/theme'], ['class' => 'ploader']) ?></li>

                        </ul>
                    </div>

                    <!-- login -->
                    <div class="h-login">

                        <?= Html::a(\Yii::$app->controller->userName, ['/admin/logout'], ['class' => 'h-login-btn']) ?>
                        <?= Html::a('Выйти из аккаунта', ['/admin/logout'], ['class' => 'h-login-btn-mobile']) ?>
                    </div>

                    <!-- menu btn -->


                </div>
            </header>


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
        </div>
    </div>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>