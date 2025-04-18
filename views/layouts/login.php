<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\LoginAssets;
use app\assets\IeAssets;
use app\assets\NewAssets;

LoginAssets::register($this);
IeAssets::register($this);
if($_GET['nCss'] == 7){
    NewAssets::register($this);
}
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
        <base href="<?=\yii\helpers\Url::base(true);  ?>">

        <link rel="preconnect" href="https://fonts.gstatic.com">

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl();?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo Yii::$app->getHomeUrl();?>favicon.ico" type="image/x-icon">
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <div class="bg">
        <div class="top-line"></div>
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
        <div class="login-header">
            <div class="login-fw">

                <!-- logo -->
                <div class="logo">
                    <a href="/login/" class="ploader old"><img src="images/logo.svg" alt="" /></a>
                    <a href="/login/" class="ploader new"><img src="images/logo-new.svg" alt="" /></a>
                </div>

                <!-- group -->
                <div class="group">
                    &nbsp;
                    <a href="tel:+74912908790">+7 (4912) 90-87-90</a>
                </div>
                <div class="group">
                    Контактный центр
                    <a href="tel:88002505078">8 (800) 250-50-78</a>
                </div>

            </div>
        </div>

        <?= $content ?>


    </div>


    <?php $this->endBody() ?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(91603005, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/91603005" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    </body>
    </html>
<?php $this->endPage() ?>