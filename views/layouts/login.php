<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\LoginAssets;

LoginAssets::register($this);
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
    <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl();?>favicon.ico" type="image/x-icon">
    <link rel="icon" href=<?php echo Yii::$app->getHomeUrl();?>favicon.ico" type="image/x-icon">
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
    <div class="login-header">
        <div class="login-fw">

            <!-- logo -->
            <div class="logo">
                <a href="/login/"><img src="images/logo.png" alt="" /></a>
            </div>

            <!-- group -->
            <div class="group">
                Горячая линия
                <a href="tel:+74912908790">+7 (4912) 90-87-90</a>
            </div>
            <div class="group">
                Общий номер
                <a href="tel:88002505078">8 (800) 250-50-78</a>
            </div>

        </div>
    </div>

    <?= $content ?>


</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>