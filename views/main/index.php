<?php

/* @var $this yii\web\View */
/* @var $result */

$this->title = 'Главная | ЛК РГМЭК';
?>
<div class="page-heading">
    <h1 class="title">Главная</h1>
</div>
<!-- main carousel -->
<div class="main-carousel">
    <div class="swiper-container">
        <div class="swiper-wrapper">
<!--            <div class="swiper-slide">-->
<!--                <a href="https://www.rgmek.ru/" target="_blank">-->
<!--                    <span class="slide" style="background-image: url(images/main_slide1.jpg);"></span>-->
<!--                </a>-->
<!--            </div>-->
            <div class="swiper-slide">
                <a href="https://www.rgmek.ru/business-clients/edo.html#ankor-1" target="_blank">
                    <span class="slide" style="background-image: url(images/main_slide2.jpg);"></span>
                </a>
            </div>
            <div class="swiper-slide">
                <a href="https://www.rgmek.ru/business-clients/edo.html#ankor-1" target="_blank">
                    <span class="slide" style="background-image: url(images/main_slide3.jpg);"></span>
                </a>
            </div>
<!--            <div class="swiper-slide">-->
<!--                <a href="https://www.rgmek.ru/">-->
<!--                    <span class="slide" style="background-image: url(images/main_slide1.jpg);"></span>-->
<!--                </a>-->
<!--            </div>-->
            <div class="swiper-slide">
                <a href="https://www.rgmek.ru/business-clients/edo.html#ankor-1" target="_blank">
                    <span class="slide" style="background-image: url(images/main_slide2.jpg);"></span>
                </a>
            </div>
            <div class="swiper-slide">
                <a href="https://www.rgmek.ru/business-clients/edo.html#ankor-1" target="_blank">
                    <span class="slide" style="background-image: url(images/main_slide3.jpg);"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<!-- summary items -->
<div class="summary-items">
    <?php if (isset($result['Contract']['Number'])) {
        echo $this->render('_summaryItem', [
            'contract' => $result['Contract'],
        ]);
    } else {
        foreach ($result['Contract'] as $arr) {
            echo $this->render('_summaryItem', [
                'contract' => $arr,
            ]);
        }
    }
    ?>
</div>
