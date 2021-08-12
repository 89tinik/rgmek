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
                        <img src="/images/logo.png" alt=""/>
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
        <div class="attach-popup custom-popup">
            <div class="message">

            </div>
            <div class="close"></div>
        </div>
        <div class="contracts-edo-popup custom-popup">
            <h2>Выберите договор</h2>

            <?= \Yii::$app->controller->listContract ?>
            <div class="close"></div>
        </div>
        <div class="loading-report-popup custom-popup">
            <h2>Внимание!</h2>
            <p>Вы указали большой расчетный период, формирование документов займет некоторое время.</p>
            <a href="#" class="btn submit-report">Далее</a>
            <a href="#" class="btn border new-date">Изменить</a>
            <div class="close"></div>
        </div>
        <div class="warning-pay-popup custom-popup">
            <h2>Уважаемый пользователь ЛК!</h2>
            <p>В настоящее время выполняется тестирование функционала оплаты!<br/>Вы можете оплатить одним из способов:
            </p>
            <ul>
                <li>через Сбербанк ОнЛ@йн, (для ИП и физических лиц);</li>
                <li>с использованием систем дистанционного банковского обслуживания по реквизитам, указанным в счете на
                    оплату.
                </li>
            </ul>
            <div class="close"></div>
        </div>
        <div class="information-pu-popup custom-popup">
            <p>Для просмотра информации о приборе учёта, кликните по его номеру.</p>
            <div class="close"></div>
        </div>
        <div class="price-category-popup custom-popup">
            <ul>
                <li>Первая ценовая категория - для объемов покупки электрической энергии (мощности), учет которых
                    осуществляется в целом за расчетный период;
                </li>
                <li>Вторая ценовая категория - для объемов покупки электрической энергии (мощности), учет которых
                    осуществляется по зонам суток расчетного периода;
                </li>
                <li>Третья ценовая категория - для объемов покупки электрической энергии (мощности), в отношении которых
                    осуществляется почасовой учет, но не осуществляется почасовое планирование, а стоимость услуг по
                    передаче электрической энергии определяется по тарифу на услуги по передаче электрической энергии в
                    одноставочном выражении;
                </li>
                <li>Четвертая ценовая категория - для объемов покупки электрической энергии (мощности), в отношении
                    которых
                    осуществляется почасовой учет, но не осуществляется почасовое планирование, а стоимость услуг по
                    передаче электрической энергии определяется по тарифу на услуги по передаче электрической энергии в
                    двухставочном выражении;
                </li>
                <li>Пятая ценовая категория - для объемов покупки электрической энергии (мощности), в отношении которых
                    за
                    расчетный период осуществляются почасовое планирование и учет, а стоимость услуг по передаче
                    электрической энергии определяется по тарифу на услуги по передаче электрической энергии в
                    одноставочном
                    выражении;
                </li>
                <li>Шестая ценовая категория - для объемов покупки электрической энергии (мощности), в отношении которых
                    за
                    расчетный период осуществляются почасовое планирование и учет, а стоимость услуг по передаче
                    электрической
                    энергии определяется по тарифу на услуги по передаче электрической энергии в двухставочном
                    выражении.
                </li>
            </ul>
            <div class="close"></div>
        </div>
        <div class="transfer-indication-popup custom-popup">
            <h3></h3>
            <div class="close"></div>
        </div>
        <div class="history-popup custom-popup">
            <p>История показаний будет доступна уже скоро. Показания за каждый расчетный период можно увидеть,
                сформировав "Детализацию счета" в разделе ЛК <?= Html::a('"Начисления и платежи"', ['main/payment', 'type-order'=>'detail'], ['class' => 'ploader link-invoice']) ?></p>
            <div class="close"></div>
        </div>
        <div id="hidden-content" style="display: none">
            <h2>Уважаемый пользователь ЛК! Данный раздел находится в разработке.</h2>
            <p>Информацию по этой теме Вы можете получить:</p>
            <ul>
                <li>на сайте, в разделе <a href="https://www.rgmek.ru/business-clients/contracts.html" target="_blank">Как
                        заключить/изменить договор</a></li>
                <li>по телефонам контактного центра +7 (4912) 90-87-90 или 8-800-250-50-78 (звонок бесплатный).</li>
            </ul>
            <p>Заявление и сканированные копии документов для заключения договора можно направить:</p>
            <ul>
                <li>в разделе <?= Html::a('"Написать обращение"', ['inner/fos'], ['class' => 'ploader']) ?>, выбрав тему
                    «Заключить/изменить договор»;
                </li>
                <li>посредством электронного документооборота Диадок,СБИС;</li>
                <li>по адресу эл. почты dronp@rgmek.ru с темой письма «Заключить/изменить договор».</li>
            </ul>

        </div>
    </div>


    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>