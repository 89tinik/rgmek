<?php

/* @var $this yii\web\View */
/* @var $result */
/* @var $piramida */
/* @var $baners */


$this->title = 'Главная | ЛК Р-Энергия';
?>
<div class="page-heading">
    <h1 class="title">Главная</h1>
</div>
<!-- main carousel -->
<div class="main-carousel">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php

            /* @var $baner \app\models\Baner */
            foreach ($baners as $baner):
                ?>
                <div class="swiper-slide">
                    <?php if (!empty($baner->link)): ?>
                        <a href="<?= $baner->link ?>" target="_blank">
                            <span class="slide" style="background-image: url(<?= $baner->path ?>);"></span>
                        </a>
                    <?php else: ?>
                        <span class="slide" style="background-image: url(<?= $baner->path ?>);"></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

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
<!--<div style="margin-top:500px;">
    <?php
print_r(getallheaders());
?>
</div>-->
