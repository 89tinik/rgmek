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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/>
    <meta name="HandheldFriendly" content="true">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- BEGIN CSS STYLES -->
    <link rel="stylesheet" href="css/fancybox.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/form-styler.css">
    <link rel="stylesheet" href="css/swiper.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/main.css">
    <!-- END CSS STYLES -->

    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

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
    <header class="header">
        <div class="fw">

            <!-- logo -->
            <div class="h-logo">
                <a href="#"><img src="images/logo.png" alt="" /></a>
            </div>

            <div class="h-label">ООО “Огонёк”</div>

            <!-- menu -->
            <div class="top-menu">
                <ul>
                    <li><a href="#">Профиль потребителя</a></li> <!-- class="active" -->
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
                <a href="#" class="h-login-btn">ООО “Огонёк”</a>
                <a href="#" class="h-login-btn-mobile">Выйти из аккаунта</a>
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
                    <a href="#">Сводка</a>
                </li>
                <li>
                    <a href="#">№0509 от 01.01.2013 <span>Парикмакхерская на Гоголя</span></a>
                    <ul>
                        <li><a href="#">Узнать задолжность, оплатить</a></li>
                        <li><a href="#">Передать показания</a></li>
                        <li><a href="#">Счета</a></li>
                        <li><a href="#">Начисления и платежи</a></li>
                    </ul>
                    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
                        <div class="title">Выбрать действие</div>
                        <div class="close">Закрыть</div>
                        <div class="bts">
                            <div class="btn full small border white">Узнать задолжность, оплатить</div>
                            <div class="btn full small border white">Передать показания</div>
                            <div class="btn full small border white">Счета</div>
                            <div class="btn full small border white">Начисления и платежи</div>
                            <div class="btn full small border white">Объекты и приборы учета</div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#">№0509 от 01.01.2013 <span>Парикмакхерская на Гоголя</span></a>
                    <ul>
                        <li><a href="#">Узнать задолжность, оплатить</a></li>
                        <li><a href="#">Передать показания</a></li>
                        <li><a href="#">Счета</a></li>
                        <li><a href="#">Начисления и платежи</a></li>
                    </ul>
                    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
                        <div class="title">Выбрать действие</div>
                        <div class="close">Закрыть</div>
                        <div class="bts">
                            <div class="btn full small border white">Узнать задолжность, оплатить</div>
                            <div class="btn full small border white">Передать показания</div>
                            <div class="btn full small border white">Счета</div>
                            <div class="btn full small border white">Начисления и платежи</div>
                            <div class="btn full small border white">Объекты и приборы учета</div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#">№0509 от 01.01.2013 <span>Парикмакхерская на Гоголя</span></a>
                    <ul>
                        <li><a href="#">Узнать задолжность, оплатить</a></li>
                        <li><a href="#">Передать показания</a></li>
                        <li><a href="#">Счета</a></li>
                        <li><a href="#">Начисления и платежи</a></li>
                    </ul>
                    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
                        <div class="title">Выбрать действие</div>
                        <div class="close">Закрыть</div>
                        <div class="bts">
                            <div class="btn full small border white">Узнать задолжность, оплатить</div>
                            <div class="btn full small border white">Передать показания</div>
                            <div class="btn full small border white">Счета</div>
                            <div class="btn full small border white">Начисления и платежи</div>
                            <div class="btn full small border white">Объекты и приборы учета</div>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#">№0509 от 01.01.2013 <span>Парикмакхерская на Гоголя</span></a>
                    <ul>
                        <li><a href="#">Узнать задолжность, оплатить</a></li>
                        <li><a href="#">Передать показания</a></li>
                        <li><a href="#">Счета</a></li>
                        <li><a href="#">Начисления и платежи</a></li>
                    </ul>
                    <div class="popup-box mobile-popup sidebar-mobile-popup" style="display: none;">
                        <div class="title">Выбрать действие</div>
                        <div class="close">Закрыть</div>
                        <div class="bts">
                            <div class="btn full small border white">Узнать задолжность, оплатить</div>
                            <div class="btn full small border white">Передать показания</div>
                            <div class="btn full small border white">Счета</div>
                            <div class="btn full small border white">Начисления и платежи</div>
                            <div class="btn full small border white">Объекты и приборы учета</div>
                        </div>
                    </div>
                </li>
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

    <!-- Call Popup
    <div class="nonebox" id="call-popup">
        <div class="title">Запишитесь на прием</div>
        <div class="subtitle">Заполните простую форму ниже* или позвоните по телефону <a href="tel+74951508100">+7 495 150-81-00</a></div>
        <form id="call_form" method="post">
            <div class="c-form">
                <div class="group">
                    <div class="label">Имя</div>
                    <div class="field name">
                        <input type="text" name="name" placeholder="Введите данные" />
                    </div>
                </div>
                <div class="group">
                    <div class="label">Телефон</div>
                    <div class="field tel">
                        <input type="tel" name="tel" placeholder="Введите номер телефона" />
                    </div>
                </div>
                <input type="submit" class="btn submit-btn" value="Отправить" />
                <div class="info-text">
                    Нажимая "отправить", Вы выражаете согласие <br />с <a href="#">политикой обработки персональных данных</a>.
                </div>
            </div>
        </form>
        <span class="close"></span>
    </div>-->

</div>

<!-- Scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.formstyler.min.js"></script>
<script src="js/jquery.fancybox.min.js"></script>
<script src="js/jquery.maskedinput.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/swiper.min.js"></script>
<script src="js/script.js"></script>
<!--<script src="js/lazysizes.min.js" async=""></script>
<script>
    /*Lazy*/
    document.addEventListener('lazybeforeunveil', function(e){
        var bg = e.target.getAttribute('data-bg');
        if(bg){
            e.target.style.backgroundImage = 'url(' + bg + ')';
        }
    });
</script>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>